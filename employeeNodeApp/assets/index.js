$(document).ready(function () {

  $("#add").click(function () {
    $("#employeeForm").toggle(500);
  });
  // Change the format of the date picker  
  $(function () {
    $('.datepicker').datepicker({
      dateFormat: 'yy-mm-dd',
      maxDate: -1,
    });
  });

  // Form submission and radio button check
  $("form#employeeForm").submit(function (event) {
    console.log("Form is being submitted");
    event.preventDefault();
    // Check if radio button is selected    
    if ($("input[type=radio]:checked").length == 0) {
      $("div.radio").html("Atleast one of the role need to be selected");
    }
    var form = $(this);
    var inputs = form.find("input");
    var serializedData = form.serialize();
    var data = serializedData;

    // Post employees
    $.post("/api/employees", data,
        function (data) {
          console.log('Posting data:')
          console.log(data);
          display_employee_list(data);
          $("#employeeForm").get(0).reset();
          $("#employeeForm").hide();
        })
      .fail(function (jqXHR, status, errorThrown) {
        console.error(" Error posting employee: " + status, errorThrown);
      });
  });

  // Get employees
  $.get("/api/employees",
      function (data) {
        console.log('Getting data:');
        console.log(data);
        display_employee_list(data);

      })
    .fail(function (jqXHR, status, errorThrown) {
      console.log(" Error getting employee list: " + status, errorThrown);
    });

  // Get By ID
  $(document).on('click', '#getByID', function () {
    console.log("Get By ID button is triggered");
    let empID = $(this).closest('td').siblings('#empNo').text()
    console.log("Employee ID:" + empID);
    $.get("/api/employees/" + empID,
        function (data) {
          console.log('Getting employee data using ID:');
          console.log(data.empID);
          $(".results").html(`<h3>Get employee By ID is successful, following employee is found:</h3>\
      Employee: \
          Name: ${data.fname} ${data.lname}, Hire Date: ${data.hdate}, Role:  ${data.role}`);
        })
      .fail(function (jqXHR, status, errorThrown) {
        $(".results").html("Employee with the given ID not found");
        console.log(" Error getting employee by ID: " + status, errorThrown);
      });
  });

  //Update employee by ID  
  $(document).on('click', '#updateByID', function () {
    console.log("Update By ID button is triggered");

    let empID = $(this).closest('td').siblings('#empNo').text();
    let empFName = $(this).closest('td').siblings('#empFName').text();
    let empLName = $(this).closest('td').siblings('#empLName').text();
    let empHDate = $(this).closest('td').siblings('#empHDate').text();
    let empRole = "VP";
    let empQuote = $(this).closest('td').siblings('#empQuote').text();
    let empJoke = $(this).closest('td').siblings('#empJoke').text();

    let updated_employee = {
      empID: empID,
      fname: empFName,
      lname: empLName,
      hdate: empHDate,
      role: empRole,
      quote: empQuote,
      joke: empJoke
    }
    $.ajax({
        url: "/api/employees/" + empID,
        type: "PUT",
        datatype: "application/json",
        data: updated_employee,
        success(data) {
          console.log('Updated employee data using ID:' + data.emp.empID);
          display_employee_list(data.emp_list);
          $(".results").html(`<h3>Updating employee is successful. The updated information is as follows:</h3> \
        Employee: \ 
        Name: ${data.emp.fname} ${data.emp.lname}, Hire Date: ${data.emp.hdate}, Role:  ${data.emp.role}`);
        }
      })
      .fail(function (jqXHR, status, errorThrown) {
        $(".getID_results").append("employee isn't updated");
        console.log(" Error updating employee by ID: " + status, errorThrown);
      });
  });


  // Delete employee by ID  
  $(document).on('click', '#deleteByID', function () {
    console.log("Delete By ID button is triggered");
    let empID = $(this).closest('td').siblings('#empNo').text()
    console.log("Employee ID:" + empID);
    $.ajax({
        url: "/api/employees/" + empID,
        type: "DELETE",
        datatype: "application/json",
        success(data) {
          console.log('Deleting employee data using ID:');
          console.log(data.emp.empID);
          display_employee_list(data.emp_list);
          $(".results").html(`<h3>Deleting employee is successful, following employee is deleted</h3> \
        Employee: \ 
        Name: ${data.emp.fname} ${data.emp.lname}, Hire Date: ${data.emp.hdate}, Role:  ${data.emp.role}`);
        }
      })
      .fail(function (jqXHR, status, errorThrown) {
        $(".getID_results").append("employee isn't deleted");
        console.log(" Error deleting employee by ID: " + status, errorThrown);
      });

  });

  // To display employee data
  function display_employee(value) {
    console.log("Displaying employee data");
    $("#empNo").append(value.empID + "</br></br>");
    $("#empFName").append(value.fname + "</br></br>");
    $("#empLName").append(value.lname + "</br></br>");
    $("#empRole").append(value.role + "</br></br>");
    $("#empHDate").append(value.hdate + "</br></br>");
    $("#empQuote").append(value.quote + "</br></br>");
    $("#empJoke").append(value.joke + "</br></br>");
  }

  // To display employee-list data
  function display_employee_list(employees) {
    console.log("Displaying employees data");
    var tbody = $('table tbody');
    tbody.html('');
    employees.forEach(function (employee) {
      tbody.append('\
        <tr>\
          <td  id="empNo">' + employee.empID + '</td>\
          <td  id="empFName">' + employee.fname + '</td>\
          <td  id="empLName"> ' + employee.lname + '</td>\
          <td  id="empHDate"> ' + employee.hdate + '</td>\
          <td  id="empRole">' + employee.role + ' </td>\
          <td  id="empQuote">' + employee.quote + '</td>\
          <td  id="empJoke">' + employee.joke + '</td>\
          <td>\
            <button type="button" class="btn btn-primary" id="getByID">Get Employee By ID</button><br><br>\
            <button type="button" class="btn btn-primary" id="updateByID">Update Employee By ID</button><br><br>\
            <button type="button" class="btn btn-primary" id="deleteByID">Delete Employee BY ID</button><br><br>\
          </td>\
        </tr>\
      ');
    })
  }

});