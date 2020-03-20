

const path = require('path');
const fs = require('fs');
const jsonFile = path.resolve('views/employee_list.json');
var axios = require('axios');
var uniqid = require('uniqid');
const { check, validationResult } = require('express-validator');


exports.test = function (req, res) {
    res.send('Greetings from the Test controller!');
};

const axiosConfig = {
    headers: {
        'Content-Type': 'application/json;charset=UTF-8',
        "Access-Control-Allow-Origin": "*",       
    }
};

exports.axiosConfig = axiosConfig;


var employees = [        
    {        
    "fname": "Alan",
    "lname": "Turing",
    "hdate": "2020-03-07",
    "role": "Manager",
    "empID": "2228000",
    "quote": "History began July 4th, 1776. Anything before that was a mistake.",
    "joke": "What did the 0 say to the 8? Nice belt."
    },
    {
    "fname": "Albert",
    "lname": "Einstein",
    "hdate": "2020-02-05",
    "role": "Manager",
    "empID": "950089",
    "quote": "History began July 4th, 1776. Anything before that was a mistake.",
    "joke": "What did the 0 say to the 8? Nice belt."
    },
    {
    "fname": "Abraham",
    "lname": "Lincoln",
    "hdate": "2020-03-07",
    "role": "CEO",
    "empID": "87699997",
    "quote": "History began July 4th, 1776. Anything before that was a mistake.",
    "joke": "What did the 0 say to the 8? Nice belt."
    },
];
   

// To validate hire date
exports.validDate = () => {
    let date_ob = new Date();
    let year = date_ob.getFullYear();
    let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
    let date = ("0" + date_ob.getDate()).slice(-2);
    let format_date = year + "-" + month + "-" + date;
    return format_date;
}

// To validate form data
exports.validate = (method) => {
    switch (method) {
      case 'create_employee': {
       return [ 
        check('fname', 'Employee First name is alphabetic only and should contain atleast 2 characters').exists().isAlpha().isLength({ min: 2 }),
        check('lname', 'Employee Last name is alphabetic only and should contain atleast 2 characters').exists().isAlpha().isLength({ min: 2 }),
        check('hdate', 'Employee Hire Date should be before today\'s date and of the format YYY-MM-DD').exists().isBefore(exports.validDate()),
        check('role', 'Employee Role/Position should be one of the following: CEO, VP, Manager, LACKEY').exists().isIn(['CEO', 'VP', 'Manager', 'LACKEY']),
        ]   
      }
    }
  }

exports.get_employees = async function (req, res, next){ 
    try {
        console.log("Getting employee list");
        //let employees = exports.read_data();          
        res.send (employees);  
    } catch(err) {
        return ("Error getting employee list from data" + err);
    }
    
}

exports.get_ByID = function (req, res){
    console.log("Getting Employee By ID");    
    try {
        let empid = req.params.id;
        console.log("Employee requested: " + empid);
        const employee = employees.find(emp => emp.empID === req.params.id);
        console.log(employee);
        if (employee) res.send(employee)
        else res.status(404).send('The employee with the given ID was not found');                
    } catch(err) {
        return ("Error getting employee by ID from data" + err);
    }
}

exports.update_ByID = async function (req, res){
    console.log("Updating employee");
    let updated_emp = req.body;  
    console.log("updated employee Info");
    console.log(updated_emp);   
    try {
        let empid = req.params.id;
        console.log("Employee being updated: " + empid);
        const employee = employees.find(emp => emp.empID === req.params.id);
        console.log(employee);  
        let index_emp = employees.indexOf(employee);
        console.log("Index of employee: " + index_emp);      
        if (employee) {
            employee.fname = updated_emp.fname;
            employee.lname = updated_emp.lname;
            employee.hdate = updated_emp.hdate
            employee.role = updated_emp.role;
            employees.splice(index_emp, 1, employee);
            res.send(employee);
        } 
        else  { res.status(404).send('The employee with the given ID coudln\'t be updated'); }               
    } catch(err) {
        return ("Error updating employee by ID from data" + err);
    }          
}

exports.delete_ByID = async function (req, res){
    console.log("Deleting employees");
    try {
        let empid = req.params.id;
        console.log("Employee requested for delete: " + empid);
        const employee = employees.find(emp => emp.empID === req.params.id);
        console.log(employee);
        let index_emp = employees.indexOf(employee);
        console.log("Index of employee: " + index_emp);   
        if (employee) {
            employees.splice(index_emp, 1);
            res.send(employee)
        }
        else res.status(404).send('The employee with the given ID couldn\t be deleted');                
    } catch(err) {
        return ("Error deleting employee by ID from data" + err);
    }           
}


exports.create_employee = async function (req, res, next) {
    try {        
        const validation_res = validationResult(req);
        if (!validation_res.isEmpty()) {            
            console.log('Error validating posted employee data');
            res.status(422).json({ errors: validation_res.array() });
            return ;
        }
        else { 
            console.log("posting data"); 
            var employee = req.body;
            const empID = uniqid();
            employee.empID = empID;
                                    
            axios.all([
                axios.get('https://ron-swanson-quotes.herokuapp.com/v2/quotes'),
                axios.get(' https://icanhazdadjoke.com/slack')
            ]).then(axios.spread((response1, response2) => {        
                let quote_data = response1.data[0];
                let j_data = response2.data.attachments;
                let joke_data = j_data.map(({ fallback }) => fallback).toString();                
                      
                if (quote_data != null && joke_data != null) {
                    employee.quote = quote_data;  
                    employee.joke = joke_data;                         
                }                               
                employees.push(employee);                
                res.send(employees); 
                res.end();  
                                        
            })).catch(error => {
                console.log(error);
              });
        }        
    }
    catch(err) {
        console.log("Error occured while creating employee record" + err);
        return next(err);       
    }
}






