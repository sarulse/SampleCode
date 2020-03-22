# Node JS App with Express Js
- A Node Js REST API CRUD app was implemented using Express Js.

### Tech Stack:
*	Languages:Node Js, HTML, CSS, JavaScript, JQuery, Ajax
*	Framework - Express Js, Bootstrap


### APP UI: http://localhost:3000/public/index.html
- Through the UI, an user can add/update/get/delete a employee record.
- Once the information is updated, it will be posted as info messages on top of the page.
- Further the employee list also shows the updated info of each employee without reloading the page.
- Redis-caching mechanism was used to fetch an existing employee record.


## Implemented End points
### POST http://localhost:3000/api/employees
- Post an employee record based on the requirements

### GET http://localhost:3000/api/employees

- Return all current records

### GET http://localhost:3000/api/employees/:id

- Return the record corresponding to the id parameter

### PUT http://localhost:3000/api/employees/:id

- Replace the record corresponding to :id with the contents of the PUT body

### DELETE http://localhost:3000/api/employees/:id

- delete the record corresponding to the id parameter


