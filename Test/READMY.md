# User ID controlled by request parameter

## 1. What we will learn

In this lab, we will see how access control can be weak when a web application relies too heavily on a URL parameter (`id`) to identify users. You will learn:

* How to scan an IP and detect open ports with **Nmap**.
* Why user input should **never** be trusted.
* How a simple parameter manipulation can bypass restrictions.
* How a small mistake can lead to full administrator privileges.

**Target IP:** `172.17.0.2`


## 2. What is Nmap?

**Nmap** ("Network Mapper") is a powerful tool used for scanning and analyzing networks. It can detect:

* Live hosts
* Open ports
* Running services
* OS versions, and more

Open a terminal and run:

```
nmap -sV 172.17.0.2
```

The result should show port `80` open, which indicates a web application is running on the target.

Open the target in your browser:

```
http://172.17.0.2:80
```

You will see a login form. Use the following credentials to log in as a normal user:

* **Username:** `socora`
* **Password:** `socora`

After logging in, you should land on your personal account page, which contains text such as:

> "This is your personal account page."

This indicates that the application personalizes content for each user.


## 4. Lab process — parameter manipulation

1. **Observe the URL** after login. The application uses an `id` parameter in the URL to decide which user’s data to display. Example:

```
user.php?id=socora
```

2. **Manually change the URL** by replacing the `id` value. For example:

```
user.php?id=administrator
```

3. **What happens?** The application assumes you are viewing the administrator’s account without properly verifying your real identity.

By modifying this parameter, you effectively bypass access control. The app accepts the new value and updates the page accordingly.


## 5. Result & impact

* Your session behaves as if you are the **administrator**, even though you logged in as a normal user.
* This grants access to features that should be restricted, for example:

  * Viewing sensitive information (such as user passwords).
  * Performing administrative actions (like deleting accounts).
  * Accessing the lab flag (proof the bypass worked).

This demonstrates how a simple change to a URL parameter can compromise access control and lead to privilege escalation.

## 6. Summary

This lab clearly demonstrates the dangers of trusting client-controlled input (URL parameters) for access control. Proper session handling and server-side role validation are essential — even a minor oversight can be exploited to gain full administrator access.
