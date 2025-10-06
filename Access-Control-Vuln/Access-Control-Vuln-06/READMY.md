# User ID Controlled by Request Parameter with Password Disclosure

**Target IP:** `172.17.0.2`  

## Objective of this Lab

In this lab, we will learn how to exploit a web vulnerability to gain **administrator-level access** using standard network analysis and web traffic tools.

### What We Will Learn

- Scanning and discovery with **Nmap** (open ports, services)  
- Capturing and manipulating web traffic with **Burp Suite** and **Repeater**  
- Analyzing **server responses**, **authentication**, and **session management**  
- Identifying and **ethically reporting** vulnerabilities


## Step 1: Scanning with Nmap

### What is Nmap?
**Nmap (“Network Mapper”)** is a powerful tool for network scanning and analysis. It can discover:

- Active hosts  
- Open ports  
- Running services  
- Operating system versions, and more  

### Command:
```
nmap -sV 172.17.0.2
```

The results show that **port 80** is open, indicating that a **web application** is running there.

Open your browser and visit:  
[http://172.17.0.2:80](http://172.17.0.2:80)

You’ll see a **login form** and a **“Support”** button.


## Step 2: Using Burp Suite (Proxy)

If you haven’t set up **Burp Suite** yet, follow these steps:
Setting Up Burp Suite
Open **Burp Suite**.

Enable **Intercept ON**.

<img src="images/burp.png" alt="Burp Suite Request Example" width="500">
   
Go to the support section and write something random; after you save it, a request like this will appear in Burp.

<img src="images/burp.png" alt="Burp Suite Request Example" width="500">


### Short note: 
When a request is saved as 3.txt, that file contains the captured request data (headers and body) and serves as a reference.
Take this request and send it to Repeater. By changing the reference from 3.txt to 2.txt, we can read the messages of other users in support.


<img src="images/burp.png" alt="Burp Suite Request Example" width="500">


When we modify it again from 2.txt to 1.txt, a conversation between the administrator and support appears, and there we find a password.

<img src="images/burp.png" alt="Burp Suite Request Example" width="500">

## Step 4: Logging into the Admin Panel

Step 4 Log in to he admin panel.

You can use the discovered credentials to log in as an administrator. 
After logging in, the admin panel appears,
- Manage or delete users  
- Retrieve the **lab’s flag**   
