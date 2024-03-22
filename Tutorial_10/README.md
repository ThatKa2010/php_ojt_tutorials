# Tutorial 10

## User Authentication And Middleware (Register,Login,Logout,Forget Password,Reset Password,Middleware)
## Table

users
- id (unsigned int, auto increment, primary key)
- name (varchar, length:255)
- email (varchar, length:255 , Not Null, Unique)
- password (varchar, length:255, Not Null)
- phone (varchar, length:255, Not Null)
- img (varchar, length:255, nullable)
- address (text, Not Null)
- created_datetime (timestamp, default value: current timestamp)
- updated_datetime (timestamp, default value: current timestamp)


## Folder Structure
```
.
css/
├── reset.css
└── style.css
demo/
└── Tuto_10.png
images/
├── example_01.png
├── example_02.png
└── ...
libs/
index.php
README.md
```

Validation
==========
- required
- email unqiue, email format
- password min:8 | max:16
- phone number max:11
- same: new password & confirm password

<hr>

## Register Page Design
![register.png](demo/register.png)

## Login Page Design
![login.png](demo/login.png)

## Forget Password Page Design
![forget_password.png](demo/forget_password.png)

## Reset Password Page Design
![reset_password.png](demo/reset_password.png)

## Home Page Design Without Auth
![home_page_design_with_no_auth.png](demo/home_page_design_with_no_auth.png)

## Home Page Design With Auth
![home_page_design_with_auth.png](demo/home_page_design_with_auth.png)

## Profile Page Design
![profile_page_design.png](demo/profile_page_design.png)
