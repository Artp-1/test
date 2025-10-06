# User ID Controlled by Request Parameter (IDOR)

**Target IP:** `172.17.0.2`  
**Vulnerability Type:** Insecure Direct Object Reference (IDOR)


## Objective of this Lab
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

### Setting Up Burp Suite
1. Open **Burp Suite** and turn **Intercept On**.  
2. On the **Home** page, you can see several comments.  
3. When you click on a comment and capture the request in Burp Suite, you will notice that each comment has a **user ID** parameter.


## Step 4: Manipulating the Request

1. Copy the **administrator's user ID**.  
2. Return to **My Account**.  
3. In Burp Suite, **modify the logged-in user’s ID (bob)** with the **administrator’s ID**.  
4. **Forward** the modified request.  

You will now gain access to the **administrator account**.


## Step 5: Administrator Access

Inside the administrator account, you can:
- Delete users  
- View all accounts  
- Retrieve the **flag**  


## What We Learned in This Lab

- How to scan a host and discover open ports and running services.  
- How to use **Burp Suite** to capture and modify HTTP requests.  
- How to identify and exploit **IDOR vulnerabilities** to gain access to other accounts.  
- The importance of **privilege controls** and the risks of **insufficient validation** of user IDs.


## Key Takeaways

- Never rely solely on client-side data for access control.  
- Validate all user requests **server-side**.  
- Use **authorization checks** to ensure users can access only their own data.  

