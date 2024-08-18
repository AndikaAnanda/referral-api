I create table users, referral, points, and APIs to implement the problem solution.

To see how the solution works, please run the server and test /register endpoint with this body request example :
- New user (without referral) :
  {
    "name": "newuser",
    "email": "newuser@gmail.com",
    "password": "newuser123",
    "password_confirmation": "newuser123"
  }
- User with referral :
  {
    "name": "seconduser",
    "email": "seconduser@gmail.com",
    "password": "seconduser123",
    "password_confirmation": "seconduser123"
    "referral_code" : *another user referral code generated in database
  }

All solutions can be seen in database 
