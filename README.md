<h2>Screenshots</h2>
<div style="display: flex; gap: 10px;">
    <h3>Landing page </h3>
 <img width="562" height="1600" alt="image" src="https://github.com/user-attachments/assets/5bd60caa-31ca-4387-8510-29877091722e" />

</div>

<div style="display: flex; gap: 10px; margin-top: 20px;">
    <h3>Events page </h3>
 <img width="1401" height="1600" alt="image" src="https://github.com/user-attachments/assets/1f22d04a-5685-4213-a3ac-4f9543331e98" />

</div>
<div style="display: flex; gap: 10px; margin-top: 20px;">
    <h3>Venue page </h3>
 <img width="1920" height="1547" alt="image" src="https://github.com/user-attachments/assets/357c4a73-f9ff-4343-9e2d-54ed2c2e6202" />


</div>
<div style="display: flex; gap: 10px; margin-top: 20px;">
    <h3>Contact us  </h3>
<img width="1920" height="2442" alt="image" src="https://github.com/user-attachments/assets/a87ffb06-487a-4b64-a306-ebe7f6c27bd4" />



</div>

<div style="display: flex; gap: 10px; margin-top: 20px;">
      <h3>About us  </h3>
<img width="616" height="1600" alt="image" src="https://github.com/user-attachments/assets/b7f76c0c-3784-4e74-9c70-bf54df3239cc" />



</div>


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
