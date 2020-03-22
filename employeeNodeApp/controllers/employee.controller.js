const path = require('path');
var axios = require('axios');
var uniqid = require('uniqid');
var employee_model = require('../models/employee.model');
var employees = employee_model.static_employees;

const {
    check,
    validationResult
} = require('express-validator');

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
        case 'create_employee':
        case 'update_employee': {
            return [
                check('fname', 'Employee First name is alphabetic only and should contain atleast 2 characters').exists().isAlpha().isLength({
                    min: 2
                }),
                check('lname', 'Employee Last name is alphabetic only and should contain atleast 2 characters').exists().isAlpha().isLength({
                    min: 2
                }),
                check('hdate', 'Employee Hire Date should be before today\'s date and of the format YYY-MM-DD').exists().isBefore(exports.validDate()),
                check('role', 'Employee Role/Position should be one of the following: CEO, VP, Manager, LACKEY').exists().isIn(['CEO', 'VP', 'Manager', 'LACKEY']),
            ]
        }
    }
}

exports.get_employees = async function (req, res, next) {
    try {
        console.log("Getting employee list");
        res.send(employees);
    } catch (err) {
        return ("Error getting employee list from data" + err);
    }

}

exports.get_ByID = function (req, res) {
    try {
        console.log("Getting employee By ID: " + req.params.id);
        const employee = employees.find(emp => emp.empID === req.params.id);
        if (employee) res.send(employee)
        else res.status(404).send('The employee with the given ID was not found');
    } catch (err) {
        return ("Error getting employee by ID from data" + err);
    }
}

exports.update_ByID = async function (req, res) {
    try {
        const validation_res = validationResult(req);

        if (!validation_res.isEmpty()) {
            console.log('Error validating updated employee data');
            res.status(422).json({
                errors: validation_res.array()
            });
            return;
        } else {
            let updated_emp = req.body;
            console.log("updating employee with ID:" + req.params.id);
            const employee = employees.find(emp => emp.empID === req.params.id);
            let index_emp = employees.indexOf(employee);
            console.log("Index of employee: " + index_emp);
            if (employee) {
                employee.fname = updated_emp.fname;
                employee.lname = updated_emp.lname;
                employee.hdate = updated_emp.hdate
                employee.role = updated_emp.role;
                employees.splice(index_emp, 1, employee);
                res.send({
                    emp_list: employees,
                    emp: employee
                });
            } else {
                res.status(404).send('The employee with the given ID coudln\'t be updated');
            }
        }
    } catch (err) {
        return ("Error updating employee by ID from data" + err);
    }
}

exports.delete_ByID = async function (req, res) {
    console.log("Deleting employee");
    try {
        console.log("Employee requested for delete: " + req.params.id);
        const employee = employees.find(emp => emp.empID === req.params.id);
        let index_emp = employees.indexOf(employee);
        console.log("Index of employee: " + index_emp);
        if (employee) {
            employees.splice(index_emp, 1);
            console.log(employees);
            res.send({
                emp_list: employees,
                emp: employee
            });
        } else res.status(404).send('The employee with the given ID couldn\'t be deleted');
    } catch (err) {
        return ("Error deleting employee by ID from data" + err);
    }
}


exports.create_employee = async function (req, res, next) {
    try {
        const validation_res = validationResult(req);
        if (!validation_res.isEmpty()) {
            console.log('Error validating posted employee data');
            res.status(422).json({
                errors: validation_res.array()
            });
            return;
        } else {
            console.log("posting data");
            var employee = req.body;
            const empID = uniqid();
            employee.empID = empID;
            // To get quote and joke data from external apis
            axios.all([
                axios.get('https://ron-swanson-quotes.herokuapp.com/v2/quotes'),
                axios.get(' https://icanhazdadjoke.com/slack')
            ]).then(axios.spread((response1, response2) => {
                let quote_data = response1.data[0];
                let j_data = response2.data.attachments;
                let joke_data = j_data.map(({
                    fallback
                }) => fallback).toString();

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
    } catch (err) {
        console.log("Error occured while creating employee record" + err);
        return next(err);
    }
}