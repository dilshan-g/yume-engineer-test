# YumeFood Tech test

## Introduction

Welcome to the YumeFood tech test! The goal here is to assess your code comprehension and ability 
to work in an existing codebase. 
This is designed to be as similar to our development environment as possible and includes some code
taken from the current codebases.

There are no required changes prior to the interview; you simply need to set up and run the project.
The interview will consist of some questions regarding the codebase and a short pair programming session.

The project is comprised of two main components:

1. A **Laravel 10** based API that also provides OAuth2 support.
2. A **Nuxt3** front-end that authenticates via the above API.

## Prerequisites

1. Make
2. Docker
3. Docker Compose
4. Git (for cloning the repo)

## Getting Started

Here are the steps to get the project up and running using Docker.

### Fork the Repository

### Build and Run the Containers

Navigate to the root directory where the `docker-compose.yml` file is located and run:

```bash
make setup
docker-compose up
```

This will build and run the `web`, `api`, and `api-db` services, linking them appropriately as specified in `docker-compose.yml`.

### Access the Applications

After all containers are up:
- Access the Nuxt3 frontend at: [http://localhost:3000](http://localhost:3000)
- Access the Laravel API at: [http://localhost:8080](http://localhost:8080)

You will be able to log in when prompted with these details:<br/>
&nbsp;&nbsp;&nbsp;&nbsp;**Username:** techtest@yumefood.com.au<br/>
&nbsp;&nbsp;&nbsp;&nbsp;**Password:** password


### Task

Create a basic CRUD (Create, Read, Update, Delete) application for managing products.

### Requirements:

#### Create an API to perform CRUD operations on products:
- <s>GET /api/products: Retrieve all products.</s>
- GET /api/products/{id}: Retrieve a specific product by its ID.
- POST /api/products: Create a new product.
- PUT /api/products/{id}: Update an existing product.
- DELETE /api/products/{id}: Delete a product.

#### Implement validation for the title field:
- Title is required.
- Title must be at least 3 characters long.

#### Write tests to cover the API endpoints using Laravel's built-in testing framework.

#### Push your changes using conventional commits

#### Bonus (if time permits)
- Implement pagination for retrieving products
- Update the product card to match the design [here](/packages/front-end/assets/img/product-card.png) - You can leave the other data in the design as static

### Time Management:

- Spend around 45 minutes on implementing the core CRUD functionality.
- Allocate the remaining time for writing tests and bonus tasks (if applicable).

This test aims to evaluate your understanding of Laravel basics, including routing, controllers, models, validation, testing and optionally pagination.
Make sure to follow Laravel's conventions and best practices while implementing the solution.
Good

## Solution

### Installation

Please follow the original instructions above to set up the project locally.

### Executing the API

* Please find the provided Postman request collection for this API and import it to your local Postman.
* Open the `Generate Oauth Token` request and generate a new Access Token.
* Copy the Access Token
* Add it to each request via the `Authorization` header as follows:
```
Bearer <the copied token>
```
### Testing and Validation

* Please run `php artisan test` to run the unit tests.
* In order to validate the user input, following actions were taken:
    * A new Request class named `ProductRequest` created to unload the validations from the Controller.
    * Checking if the product ID provided is a number.
* The user input will be sanitised/escaped by the already existing two middlewares [here](https://github.com/dilshan-g/yume-engineer-test/blob/main/packages/back-end/app/Http/Kernel.php#L22-L23). No extra actions were taken to sanitise the input.
