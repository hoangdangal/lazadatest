#setup source code:
_ copy folder lazadatest to your host folder of web server.

_ rename env.txt file to .env

_ to setup email , edit file .env in folder lazadatest :

    MAIL_DRIVER=smtp
    
    MAIL_HOST=smtp.gmail.com
    
    MAIL_PORT=465
    
    MAIL_USERNAME=someone@gmail
    
    MAIL_PASSWORD=12345
    
    MAIL_ENCRYPTION=ssl

    MAIL_FROM_ADDRESS=someone@gmail
    
    MAIL_TO=someone@gmail
    
    POST_CREATED_EMAIL_HEADER = "Post created alert"
    
_ config database , edit file .env in folder lazadatest :

    DB_CONNECTION=mysql
    
    DB_HOST=127.0.0.1
    
    DB_PORT=3306
    
    DB_DATABASE=lazadatest
    
    DB_USERNAME=root
    
    DB_PASSWORD=123456

#setup database :
_ import database from script file in folder database.

_ at local , mysql account is : root , password : 123456.Change it if you need.

#url
At my pc , I user url : http://localhost/lazadatest/public/

Url of Swagger UI tool to test API : http://localhost/lazadatest/public/swagger/
