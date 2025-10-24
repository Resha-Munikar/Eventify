<h2>Screenshots</h2>
<div style="display: flex; gap: 10px;">
 <img width="562" height="1600" alt="image" src="https://github.com/user-attachments/assets/5bd60caa-31ca-4387-8510-29877091722e" />

</div>

<div style="display: flex; gap: 10px; margin-top: 20px;">
 <img width="1401" height="1600" alt="image" src="https://github.com/user-attachments/assets/1f22d04a-5685-4213-a3ac-4f9543331e98" />

</div>



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
