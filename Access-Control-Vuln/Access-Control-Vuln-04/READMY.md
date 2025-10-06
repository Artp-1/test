# User ID Controlled by Request Parameter (IDOR)

**Target IP:** `172.17.0.2`  

In this exercise, we will learn:

- How to use **Nmap** to scan open ports and detect running services.  
- How to use **Burp Suite** to capture and modify HTTP requests.  
- How to analyze user IDs and understand the risks of **IDOR (Insecure Direct Object References)**.  
- How to understand the effects of elevated privileges and user roles.


## Step 1: Scanning with Nmap

### What is Nmap?
Nmap (“Network Mapper”) is a powerful tool for scanning and analyzing networks. It can detect:

- Live hosts  
- Open ports  
- Running services  
- Operating system versions, and more  

**Command used:**
```
nmap -sV 172.17.0.2
```

The results show that **port 80** is open, which means a web application is running on that port.

Open your browser and visit:  
[http://172.17.0.2:80](http://172.17.0.2:80)


## Step 2: Logging into the Web Application

You will see a **login form**. Use the following credentials:

```
Username: socora
Password: socora
```

After logging in as `socora`, you will see an option **Home**.


## Step 3: Using Burp Suite

If you haven’t set up Burp Suite yet, follow these steps:
Setting Up Burp Suite

### Setting Up Burp Suite
Open **Burp Suite** and turn **Intercept On**.

<img src="../images/Screenshot%202025-10-06%20at%2010.00.55.png" alt="Burp Suite Intercept" width="600">





On the **Home** page, you can see several comments.  
When you click on a comment and capture the request in Burp Suite, you will notice that each comment has a **user ID** parameter.


<img src="images/burp.png" alt="Burp Suite Request Example" width="500">

Copy the administrator's ID and return to My Account.
In Burp, modify the logged-in user's ID (socora) with the administrator’s ID.

<img src="images/burp.png" alt="Burp Suite Request Example" width="500">

Forward the modified request, and you will be able to access the administrator account.
In the administrator account, you can delete users and find the flag.


## What We Learned in This Lab

- How to scan a host and discover open ports and running services.  
- How to use **Burp Suite** to capture and modify HTTP requests.  
- How to identify and exploit **IDOR vulnerabilities** to gain access to other accounts.  
- The importance of **privilege controls** and the risks of **insufficient validation** of user IDs.

