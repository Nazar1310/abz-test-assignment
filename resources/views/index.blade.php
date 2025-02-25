<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const API_URL = 'https://kravchuk-nazar.online/api';
    </script>
</head>
<body>
<div class="container mt-5">
    <h1>Login</h1>
    <form id="registerForm">
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" id="email" class="form-control" required placeholder="test@gmail.com">
        </div>

        <div class="mb-3">
            <label>Password:</label>
            <input type="password" id="password" class="form-control" required placeholder="qwerty123">
        </div>

        <button type="submit" class="btn btn-primary">Login & Get Token</button>
    </form>

    <hr>

    <h2>Add New User</h2>
    <form id="createUserForm" enctype="multipart/form-data" style="display: none">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required placeholder="min:8">
        </div>

        <div class="mb-3">
            <label>Photo:</label>
            <input type="file" name="photo" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Add User</button>
    </form>

    <hr>

    <h1>Users List</h1>
    <div id="userList" class="row"></div>
    <button id="loadUsers" class="btn btn-secondary mb-4">Load More Users</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    if (localStorage.getItem('auth_token')) {
        document.getElementById('createUserForm').style.display = 'block';
    }
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await axios.post(`${API_URL}/login`, {
                email,
                password
            });

            const token = response.data.access_token;
            localStorage.setItem('auth_token', token);
            alert('Token saved successfully!');

            window.location.href = '/';
        } catch (error) {
            alert('Invalid credentials!');
        }
    });

    const perPage = 6;
    let page = 1;

    async function loadUsers() {
        try {
            const response = await axios.get(`${API_URL}/users?per_page=${perPage}&page=${page}`);
            if(response.statusText === "OK") {
                page ++;
            }
            const users = response.data;

            let userListHtml = '';
            users.forEach(user => {
                userListHtml += `
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="${user.photo_url}" class="card-img-top" style="height: 70px; width: 70px; object-fit: cover; margin: auto; margin-top: 10px;">
                        <div class="card-body">
                            <h5 class="card-title">${user.name}</h5>
                            <p class="card-text">
                                Email: ${user.email} <br>
                            </p>
                        </div>
                    </div>
                </div>`;
            });

            document.getElementById('userList').insertAdjacentHTML('beforeend', userListHtml);
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    loadUsers();

    document.getElementById('loadUsers').addEventListener('click', async () => {
        await loadUsers();
    });

    document.getElementById('createUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const token = localStorage.getItem('auth_token');
        if (!token) {
            alert('Invalid token!');
        } else {
            try {
                await axios.post(`${API_URL}/users`, formData, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'multipart/form-data'
                    }
                });

                alert('User created successfully!');
                form.reset();
                window.location.href = '/';
            } catch (error) {
                console.error('Error creating user:', error);
                alert('Error creating user.');
            }
        }
    });
</script>
</body>
</html>
