# User Role Controlled by Request Parameter

**Lab IP:** 172.17.0.2

## 1: Initial Reconnaissance

We start by running an nmap scan to identify open services on the host.

### What is Nmap?

Nmap (Network Mapper) is a powerful network scanning tool used to
discover hosts, open ports, and running services within a system.

**Command used:**

```
nmap -sV 172.17.0.2
```

The scan results show that **port 80 is open**, which indicates that a
The web application is running on this host.


Open the browser and navigate to:

    http://172.17.0.2:80

On the main page, we see a **login form**.

We test the following credentials:
- **Username:** socora
- **Password:** socora

After logging in successfully, we are signed in as user **socora**.



## Step 2: Understanding User Roles

### What is a User Role?

A user role defines the level of access a user has within a system.\
Typical examples include: - **Admin:** Full access to all
functionalities and data. - **Editor:** Can create or modify content,
but cannot manage users. - **User:** Restricted to basic features only.

A secure system must always enforce roles **on the server side**, not
based on client-controlled input.



## Step 3: The Vulnerability

In this vulnerability, a user's role is determined by **parameters
passed in a request** (e.g., URL, form, or cookies).

**Example:**

    GET /dashboard?role=admin

If the server fails to validate this properly, a normal user can simply
modify the value (e.g., `role=user` → `role=admin`) and gain
unauthorized administrative privileges.

The issue arises because the server trusts client-side input instead of
relying solely on secure, authenticated session data.

## Step 5: Exploitation

After logging in as **socora**, open: - **Inspect → Application → Cookies**


<img src="Screenshot 2025-10-02 at 15.56.24.png" width="300">



<img src="Screenshot 2025-10-03 at 11.17.50.png" width="700">

Locate the parameter:

    Admin=false

Edit the value to:

    Admin=true

Refresh the page.
Now, the **Admin Panel** becomes visible. From here, we can delete users
and retrieve the **flag**.

## Lessons Learned

-   Never trust client-side input for critical security functions
    like access control.
-   User roles must always be enforced on the **server side**, not
    through request parameters.
-   A poorly implemented role check can lead to **privilege
    escalation**, allowing attackers to gain admin access and compromise
    sensitive data.

