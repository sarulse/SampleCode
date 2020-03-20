const express = require('express');
const router = express();
const employee_controller = require('../controllers/employee.controller');


router.get('/', employee_controller.get_employees);

router.get('/:id', employee_controller.get_ByID);

router.put('/:id', employee_controller.update_ByID);

router.delete('/:id', employee_controller.delete_ByID);

router.post('/', employee_controller.validate('create_employee'), employee_controller.create_employee);

module.exports = router;