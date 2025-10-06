# User Role Can Be Modified in User Profile

**Target IP:** `172.17.0.2`  

## Objective of this Lab
In this exercise, we will learn:

- How an update profile form works in a web application.  
- How to use **Burp Suite** to capture and modify HTTP requests.  
- How to exploit a vulnerability in the **role ID** to gain access to the admin panel.  
- How a **Privilege Escalation** can occur — turning a normal user into an administrator.


## Step 1: Scanning with Nmap

### What is Nmap?
Nmap ("Network Mapper") is a powerful tool for scanning and analyzing networks. It can detect:

- Live hosts  
- Open ports  
- Running services  
- OS versions, and more  

**Command used:**
```
nmap -sV 172.17.0.2
```

The results show that **port 80** is open — meaning a web application is running there.

Open your browser and visit:  
👉 [http://172.17.0.2:80](http://172.17.0.2:80)


## Step 2: Logging into the Web Application

You’ll see a **login form**. Use the following credentials:

```
Username: socora
Password: socora
```

After logging in as `socora`, you’ll notice an option to **update your email** in the user profile section.


## Step 3: Using Burp Suite

If you haven’t set up Burp Suite yet, follow these walkthrought:
Setting Up Burp Suite
### Setting Up Burp Suite
1. Open Burp Suite and turn **Intercept On**.  
2. Go to the **Update Email** function in the web app.  
3. Change your email, for example from:
   ```
   socora@example.com
   ```
   to:
   ```
   maria@example.com
   ```


## Step 4: Manipulating the Request

In Burp Suite, you’ll see a **POST request** like this:
```
email=maria%40example.com
```

The vulnerability lies in the fact that the application also accepts a **hidden parameter** called `roleid`.

```
roleid=0 → normal user
roleid=1 → administrator
```

So, modify the request as follows:
```
email=maria%40example.com&roleid=1
```
![My Photo](unknown.png)


Then **Forward** the request and refresh the page.


## Step 5: Administrator Access

Now, our account has been **escalated to admin privileges**, and the **Admin Panel** becomes visible.

From here we can:
- View all users  
- Delete users  
- Retrieve the **flag**


## Summary

Through this lab, we demonstrated how an **insecure profile update form** can be exploited for **Privilege Escalation**, granting full administrator access.


## Key Takeaways

- Never trust hidden parameters from the client side.  
- Always verify user roles **server-side**.  
- Implement proper **access control** and **authorization checks**.  

