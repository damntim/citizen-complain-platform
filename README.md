# 🇷🇼 Citizen Complaints and Engagement System (MVP)

A modern, multilingual (Kinyarwanda 🇷🇼 as default, plus English 🇬🇧 and French 🇫🇷) web platform that empowers Rwandan citizens to submit complaints or questions to public institutions, track the progress, and get updates through SMS or email. This MVP focuses on accessibility, transparency, and agent-citizen engagement.



 🚀 Introduction

This project is a fully responsive web application designed to bridge the communication gap between citizens and public institutions. Built with simplicity and functionality in mind, it enables complaint handling and engagement at scale.

Core Features:
- 🔄 Multilingual interface (Kinyarwanda default, English, French)
- 📲 Real-time notifications via SMS & Email
- 👩‍💼 Agent-controlled ticket management
- 🏛️ Institution & service routing
- 🧠 Simple dashboard with analytics
- 🔐 Secure invitation-only admin & agent registration



 🧑‍💻 How It Works (For Everyone)

 🧍‍♂️ For Citizens

1. Submit Complaint ("Ohereza Ikibazo")  
   - Click the button to open a simple form in a popup/modal.
   - Fill out your information: name, phone, select institution/service, describe issue.
   - Choose delivery method (SMS/email).
   - Submit the ticket. You’ll get notified when it's received and when responses are added.

2. Track Status ("Kureba Aho Bigeze")  
   - Enter your phone number or ticket number.
   - See ticket status (new, ongoing, completed), responses, and feedback option.
   - If not satisfied, you can open a follow-up chat. The agent gets notified to respond again.



 🧑‍💼 For Admin

- Create your account during the first-time setup.
- Invite other agents or staff using email invitations.
- Manage:
  - Agents
  - Institutions & services
  - View analytics and ticket trends via the dashboard



 🖥️ System Interfaces

 1. 📊 Dashboard (Landing After Login)
- Minimal but functional.
- Displays:
  - Number of citizens
  - Ticket statistics
  - Service activity overview

 2. 🧑 Agents Page
- View list of registered agents
- Manage their details

 3. 📨 Ticket Management
- Tabs:
  - New: View all unassigned tickets. Click “Proceed” to take it, which notifies the citizen.
  - Ongoing: View tickets assigned to you. Add responses and notify the citizen.
  - Completed: View your resolved tickets for record keeping.

 4. 🏛️ Institutions & Services
- Admins can add institutions and services they offer.
- Citizens choose these while submitting their complaints so tickets are routed correctly.



 📩 Communication Features

 ✅ SMS & Email Notifications
- Sent when:
  - Ticket is received
  - Ticket is responded to
  - Ticket is marked complete

 🧪 SMS Delivery Testing
- Test framework in place to ensure SMS is reliably delivered and debug any delivery issues.

 🔮 Future Plans
- USSD Integration: Citizens will submit complaints & track them via short codes without needing internet.
- Scheduled Video Support: Citizens will be able to schedule video meetings with agents for deeper issues.



 🛠️ Tech Stack

- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP 
- Database: MySQL
- Notifications: Pushbullet SMS Gateway API & Email (SMTP)
- Languages: Multilingual support using translation files
- Deployment: Web-based 



 🔐 Account Creation & Access Control

- ✅ First admin account created manually during initial setup.
- ✉️ Other users (agents/admins) join via invitation only.
- 🛑 No public sign-up – access is controlled and secure.

link: https://citizen.free.nf/index.php for demo

📬 Contact
For technical help or partnership, please contact the project lead:

Name: mbarushimana danny
Email: ykdann53@gmail.com
tel: 2500785498054
Location: Rwanda 🇷🇼

