<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const API_URL = '{{env('APP_URL')}}/api';
    </script>
</head>
<body>
<div class="container mt-5">
    <button id="getToken" class="btn btn-primary">Get Token</button>

    <hr>

    <form id="createUserForm" enctype="multipart/form-data" style="display: none">
        <h2>Add New User</h2>
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Position:</label>
            <select id="positionSelect" name="position_id" class="form-control" required></select>
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
    document.getElementById('getToken').addEventListener('click', async () => {
        try {
            const response = await axios.get(`${API_URL}/token`);
            const data = response.data;

            if (data.success) {
                localStorage.setItem('auth_token', data.token);
                alert('Token saved successfully!');
                window.location.href = '/';
            }
        } catch (error) {
            alert('Error');
        }
    });

    async function loadPositions() {
        try {
            const response = await axios.get(`${API_URL}/positions`);
            const data = response.data;

            if (data.success) {
                let positionListHtml = '';
                data.positions.forEach(position => {
                    positionListHtml += `<option value="${position.id}">${position.name}</option>`;
                });

                document.getElementById('positionSelect').innerHTML = positionListHtml;
            }
        } catch (error) {
            console.error('Error loading positions:', error);
        }
    }

    loadPositions();

    const perPage = 6;
    let page = 1;

    async function loadUsers() {
        try {
            const response = await axios.get(`${API_URL}/users?count=${perPage}&page=${page}`);
            const data = response.data;

            if (data.success) {
                if (data.total_pages > page) {
                    page ++;
                }
                let userListHtml = '';
                data.users.forEach(user => {
                    userListHtml += `
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="${user.photo}" class="card-img-top" style="height: 70px; width: 70px; object-fit: cover; margin: auto; margin-top: 10px;">
                            <div class="card-body">
                                <h5 class="card-title">${user.name}</h5>
                                <p class="card-text">
                                    Position: ${user.position} <br>
                                    Email: ${user.email} <br>
                                    Phone: ${user.phone} <br>
                                </p>
                            </div>
                        </div>
                    </div>`;
                });

                document.getElementById('userList').insertAdjacentHTML('beforeend', userListHtml);
            }
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
        try {
            const response = await axios.post(`${API_URL}/users`, formData, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data'
                }
            });
            const data = response.data;

            if (data.success) {
                alert(data.message);
                window.location.href = '/';
            }
        } catch (error) {
            alert('Error creating user.');
        }
    });
</script>
</body>
</html>
