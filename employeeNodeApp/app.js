'use strict';

const express = require('express');
const employeeRoutes = require('./routes/employee.route');
const app = express();
const port = parseInt(process.env.PORT || '3000');
const path = require('path');
const bodyParser = require('body-parser');


app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use('/public', express.static(path.join(__dirname, 'views')))
app.use('/static', express.static(path.join(__dirname, 'assets')))

app.use('/api/employees', employeeRoutes);

// Fail over route
app.use(function(req, res) {
    res.status(404).send('Not found');
});

// listen for requests
app.listen(port, function() {
    console.log(`Server is listening on port ${port}`);
});

module.exports = app;