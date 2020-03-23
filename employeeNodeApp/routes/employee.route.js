const express = require('express');
const router = express();
const employee_controller = require('../controllers/employee.controller');
const responseTime = require('response-time');

router.use(express.json());
router.use(responseTime());

router.get('/', employee_controller.get_employees);

router.get('/:id', employee_controller.get_ByID);

router.put('/:id', employee_controller.validate('update_employee'), employee_controller.update_ByID);

router.delete('/:id', employee_controller.delete_ByID);

router.post('/', employee_controller.validate('create_employee'), employee_controller.create_employee);

module.exports = router;