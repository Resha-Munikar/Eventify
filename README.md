#Screenshot 
landing page :
<img width="200" height="100" alt="image" src="https://github.com/user-attachments/assets/acac651c-b403-445f-b1bb-12666305504b" />
About Us:
<img width="200" height="100" alt="image" src="https://github.com/user-attachments/assets/64fc2fc1-2032-469a-826b-c7523bf8cd1f" />
Events:
<img width="200" height="100" alt="image" src="https://github.com/user-attachments/assets/7139ace3-c03e-4f08-9cde-898beb65189a" />



# Project setup
1. clone the repository:
```
https://github.com/Resha-Munikar/Eventify.git
```
2. Install Dependencies:
   ```
   cd blog
   composer install
   npm install
   ```
3. Database Setup:
    - Copy the .env.example file to .env and configure your database settings.
    - Run database migrations:
   ```
   php artisan migrate
   ```
4. Generate Application Key:
   ```
   php artisan key:generate
   ```
5. Serve the Application:
   ```
   composer run dev
   ```
6. Open in web browser:
   ```
   http://127.0.0.1:8000/
   ```
**Note:** Vite and Tailwind CSS are auto configured with every Laravel projects. No need to manually configure them.
