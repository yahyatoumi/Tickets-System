# Ticket System Manager  

This repository hosts a **Ticket System Manager** built with **Laravel**, **Inertia.js**, and **Vue.js**. The application provides an efficient platform for managing tickets, with distinct roles and functionalities for **Admins**, **Supervisors**, and **End Users**.  

## Key Features  

1. **Role-Based Ticket Management**  
   - **End Users**: Can submit tickets describing issues or requests.  
   - **Supervisors**: Handle assigned tickets, update progress, and resolve them. They can also perform all End User actions.  
   - **Admins**: Assign tickets to Supervisors and oversee the process. They can also perform all Supervisor actions.  

2. **Real-Time Notifications**  
   - Notifications are sent to Admins for every action or event related to a ticket, such as creation, updates, or comments.  
   - Other users receive notifications for relevant ticket updates and comments.  

3. **Commenting System**  
   - Users can add comments on tickets, ensuring clear communication between all parties.  

4. **Status Updates**  
   - Supervisors can update the ticket status (e.g., "Resolved" after the issue is fixed).  

5. **File Attachments**  
   - Users can attach files to tickets for better issue description or context.

## Application Roles  

### **End Users**  
- Submit tickets with details about issues or requests.  
- Receive notifications for ticket updates and comments.  

### **Supervisors**  
- Handle tickets assigned to them by Admins.  
- Update ticket statuses and leave comments.  
- Perform all End User actions.  

### **Admins**  
- Assign submitted tickets to Supervisors.  
- Monitor ticket progress and user activity.  
- Perform all Supervisor actions.  
- Receive notifications for **all events** related to tickets, ensuring complete oversight.  

## Technologies Used  

- **Laravel**: Back-end framework to manage business logic and API endpoints.  
- **Inertia.js**: A bridge between Laravel and Vue.js for single-page application functionality.  
- **Vue.js**: Front-end framework to create dynamic user interfaces.  
- **PostgreSQL**: Database for storing application data. 

### For docker-env branch
- **Redis**: For caching and managing real-time notifications.  
- **Docker**: To containerize and manage the application in a consistent environment.  

## Installation  

1. Install dependencies:  
   ```bash
   composer install
   npm install
   ```  

2. Set up the `.env` file:  
   - Copy `.env.example` to `.env`.  
   - Update database and Redis configurations as needed.  
   - **Ensure PostgreSQL is running and listening on the correct port, and the credentials in `.env` match the database setup.**  

3. Generate the application key:  
   ```bash
   php artisan key:generate
   ```  

4. Run migrations and seeders:  
   ```bash
   php artisan migrate --seed
   ```  

5. Start the development server:  
   ```bash
   php artisan serve
   npm run dev
   ```  

6. Start the Reverb server:  
   ```bash
   php artisan reverb:start
   ```  

7. Start the queue worker:  
   ```bash
   php artisan queue:work
   ```  
   - **Ensure your `.env` file is correctly configured with Redis settings to handle queued jobs efficiently.**  

**Note:** Double-check that PostgreSQL is properly configured, listening, and connected.

## Notifications and Real-Time Updates  

- **Admins** receive notifications for **all events** on any ticket, ensuring they are always informed.  
- **End Users** and **Supervisors** receive notifications for ticket updates and comments relevant to them.  
- The app uses **Reverb** and **Laravel Echo** for broadcasting real-time notifications.  

## Development Notes  

1. **Role Management**  
   Roles are managed in the database. Ensure users are assigned the correct roles (Admin, Supervisor, End User).  

2. **Ticket Comments**  
   Comments are displayed in real-time for better collaboration. 