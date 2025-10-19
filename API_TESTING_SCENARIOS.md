# API Testing Scenarios - Mini Issue Tracker

This document provides comprehensive testing scenarios for all API routes in the Mini Issue Tracker application.

## Prerequisites

1. **Run Migrations and Seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

2. **Start the Server**
   ```bash
   php artisan serve
   ```

3. **Base URL**: `http://localhost:8000/api`

4. **Test Credentials**:
   - Email: `admin@issuetracker.com`
   - Password: `password123`

---

## 1. Authentication Routes

### 1.1 Register New User
**Endpoint**: `POST /api/auth/register`

**Request Body**:
```json
{
    "name": "New User",
    "email": "newuser@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Expected Response**: `201 Created`
```json
{
    "data": {
        "user": {
            "id": 12,
            "name": "New User",
            "email": "newuser@test.com"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
    },
    "message": "User registered successfully",
    "status": 201
}
```

---

### 1.2 Login
**Endpoint**: `POST /api/auth/login`

**Request Body**:
```json
{
    "email": "admin@issuetracker.com",
    "password": "password123"
}
```

**Expected Response**: `200 OK`
```json
{
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@issuetracker.com"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
    },
    "message": "Login successful",
    "status": 200
}
```

**Save the token for subsequent requests!**

---

### 1.3 Logout
**Endpoint**: `POST /api/auth/logout`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`
```json
{
    "data": null,
    "message": "Logout successful",
    "status": 200
}
```

---

## 2. Project Routes

### 2.1 List All Projects
**Endpoint**: `GET /api/projects`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`
```json
{
    "data": [
        {
            "id": 1,
            "name": "E-Commerce Platform",
            "description": "A modern e-commerce platform...",
            "code": "ECOM",
            "created_at": "2025-10-20T00:00:00.000000Z",
            "updated_at": "2025-10-20T00:00:00.000000Z"
        }
    ],
    "message": "Projects retrieved successfully",
    "status": 200
}
```

---

### 2.2 Create New Project
**Endpoint**: `POST /api/projects`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "Social Media App",
    "description": "A new social media platform for developers",
    "code": "SOCIAL"
}
```

**Expected Response**: `201 Created`

---

### 2.3 Get Single Project
**Endpoint**: `GET /api/projects/{id}`

**Example**: `GET /api/projects/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 2.4 Update Project
**Endpoint**: `PUT /api/projects/{id}`

**Example**: `PUT /api/projects/1`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "E-Commerce Platform v2",
    "description": "Updated description for the e-commerce platform"
}
```

**Expected Response**: `200 OK`

---

### 2.5 Delete Project
**Endpoint**: `DELETE /api/projects/{id}`

**Example**: `DELETE /api/projects/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

## 3. Project User Management

### 3.1 Add User to Project
**Endpoint**: `POST /api/projects/{project_id}/users`

**Example**: `POST /api/projects/1/users`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "user_id": 2,
    "role": "developer"
}
```

**Roles**: `developer`, `manager`, `tester`

**Expected Response**: `201 Created`

---

### 3.2 Remove User from Project
**Endpoint**: `DELETE /api/projects/{project_id}/users`

**Example**: `DELETE /api/projects/1/users`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "user_id": 2
}
```

**Expected Response**: `200 OK`

---

## 4. Issue Routes

### 4.1 List All Issues
**Endpoint**: `GET /api/issues`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK` (Paginated)

---

### 4.2 Create New Issue
**Endpoint**: `POST /api/issues`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "title": "Fix login bug",
    "description": "Users cannot login with special characters in password",
    "status": "open",
    "priority": "high",
    "project_id": 1,
    "assigned_to": 2,
    "code": "ECOM-100",
    "due_window": {
        "due_at": "2025-11-01 12:00:00",
        "remind_before": "2 days"
    }
}
```

**Status Values**: `open`, `in_progress`, `completed`
**Priority Values**: `lowest`, `low`, `medium`, `high`, `highest`

**Expected Response**: `201 Created`

---

### 4.3 Get Single Issue
**Endpoint**: `GET /api/issues/{id}`

**Example**: `GET /api/issues/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 4.4 Update Issue
**Endpoint**: `PUT /api/issues/{id}`

**Example**: `PUT /api/issues/1`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "status": "in_progress",
    "priority": "highest"
}
```

**Expected Response**: `200 OK`

---

### 4.5 Delete Issue
**Endpoint**: `DELETE /api/issues/{id}`

**Example**: `DELETE /api/issues/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 4.6 Get Opened Issues
**Endpoint**: `GET /api/issues/opened`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 4.7 Get Urgent Issues
**Endpoint**: `GET /api/issues/urgent`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 4.8 Get Completed Issues Count for User
**Endpoint**: `GET /api/users/{user_id}/completed-issues-count`

**Example**: `GET /api/users/1/completed-issues-count`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 4.9 Get Open Issues of Project
**Endpoint**: `GET /api/projects/{project_id}/opened-issues`

**Example**: `GET /api/projects/1/opened-issues`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

## 5. Label Routes

### 5.1 List All Labels
**Endpoint**: `GET /api/labels`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK` (Paginated)

---

### 5.2 Create New Label
**Endpoint**: `POST /api/labels`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "critical",
    "color": "#ff0000"
}
```

**Expected Response**: `201 Created`

---

### 5.3 Get Single Label
**Endpoint**: `GET /api/labels/{id}`

**Example**: `GET /api/labels/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 5.4 Update Label
**Endpoint**: `PUT /api/labels/{id}`

**Example**: `PUT /api/labels/1`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "bug-critical",
    "color": "#cc0000"
}
```

**Expected Response**: `200 OK`

---

### 5.5 Delete Label
**Endpoint**: `DELETE /api/labels/{id}`

**Example**: `DELETE /api/labels/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

## 6. Issue Label Management

### 6.1 Attach Labels to Issue
**Endpoint**: `POST /api/projects/{project_id}/issues/{issue_id}/labels`

**Example**: `POST /api/projects/1/issues/1/labels`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "label_ids": [1, 2, 3]
}
```

**Expected Response**: `200 OK`

---

### 6.2 Sync Labels with Issue
**Endpoint**: `PUT /api/projects/{project_id}/issues/{issue_id}/labels`

**Example**: `PUT /api/projects/1/issues/1/labels`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "label_ids": [4, 5]
}
```

**Note**: This replaces all existing labels with the new ones.

**Expected Response**: `200 OK`

---

### 6.3 Detach Label from Issue
**Endpoint**: `DELETE /api/projects/{project_id}/issues/{issue_id}/labels/{label_id}`

**Example**: `DELETE /api/projects/1/issues/1/labels/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `204 No Content`

---

## 7. Comment Routes

### 7.1 List Comments for Issue
**Endpoint**: `GET /api/projects/{project_id}/issues/{issue_id}/comments`

**Example**: `GET /api/projects/1/issues/1/comments`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK` (Paginated)

---

### 7.2 Create Comment
**Endpoint**: `POST /api/projects/{project_id}/issues/{issue_id}/comments`

**Example**: `POST /api/projects/1/issues/1/comments`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "body": "This is a test comment. I'm working on fixing this issue."
}
```

**Expected Response**: `201 Created`

---

### 7.3 Get Single Comment
**Endpoint**: `GET /api/comments/{id}`

**Example**: `GET /api/comments/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 7.4 Update Comment
**Endpoint**: `PUT /api/comments/{id}`

**Example**: `PUT /api/comments/1`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "body": "Updated comment text"
}
```

**Expected Response**: `200 OK`

---

### 7.5 Delete Comment
**Endpoint**: `DELETE /api/comments/{id}`

**Example**: `DELETE /api/comments/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `204 No Content`

---

### 7.6 Get Comments Count for Issue
**Endpoint**: `GET /api/projects/{project_id}/issues/{issue_id}/comments-count`

**Example**: `GET /api/projects/1/issues/1/comments-count`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

## 8. User Routes

### 8.1 List All Users
**Endpoint**: `GET /api/users`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK` (Paginated)

---

### 8.2 Get Single User
**Endpoint**: `GET /api/users/{id}`

**Example**: `GET /api/users/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

### 8.3 Update User
**Endpoint**: `PUT /api/users/{id}`

**Example**: `PUT /api/users/1`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body**:
```json
{
    "name": "Updated Name",
    "email": "updated@email.com"
}
```

**Expected Response**: `200 OK`

---

### 8.4 Delete User
**Endpoint**: `DELETE /api/users/{id}`

**Example**: `DELETE /api/users/1`

**Headers**:
```
Authorization: Bearer {token}
```

**Expected Response**: `200 OK`

---

## 9. Complete Testing Scenario

Here's a complete workflow to test the entire system:

### Step 1: Authentication
1. Register a new user
2. Login with the new user
3. Save the authentication token

### Step 2: Create Project
1. Create a new project
2. Get the project ID from the response

### Step 3: Add Team Members
1. Add 2-3 users to the project with different roles
2. Verify users are added

### Step 4: Create Labels
1. Create 3-4 labels (bug, feature, urgent, etc.)
2. Get label IDs

### Step 5: Create Issues
1. Create 5 issues with different priorities and statuses
2. Assign issues to different users
3. Set due dates for some issues

### Step 6: Attach Labels to Issues
1. Attach multiple labels to each issue
2. Test sync operation to replace labels
3. Test detach operation

### Step 7: Add Comments
1. Add 3-5 comments to each issue
2. Update some comments
3. Delete a comment

### Step 8: Query and Filter
1. Get all opened issues
2. Get urgent issues
3. Get completed issues count for a user
4. Get open issues for a specific project
5. Get comments count for an issue

### Step 9: Update Operations
1. Update issue status from 'open' to 'in_progress'
2. Update issue priority
3. Reassign issue to another user
4. Update project details

### Step 10: Cleanup
1. Delete some comments
2. Remove labels from issues
3. Remove users from project
4. Delete issues
5. Delete project

---

## 10. Testing Tools

### Using Postman
1. Import the routes into Postman
2. Set up environment variables for:
   - `base_url`: http://localhost:8000/api
   - `token`: (set after login)
3. Use `{{base_url}}` and `{{token}}` in requests

### Using cURL Examples

**Login**:
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@issuetracker.com","password":"password123"}'
```

**Get Projects** (replace TOKEN with actual token):
```bash
curl -X GET http://localhost:8000/api/projects \
  -H "Authorization: Bearer TOKEN"
```

**Create Issue**:
```bash
curl -X POST http://localhost:8000/api/issues \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Issue",
    "description": "Testing issue creation",
    "status": "open",
    "priority": "high",
    "project_id": 1,
    "assigned_to": 2,
    "code": "TEST-1"
  }'
```

---

## 11. Expected Database State After Seeding

- **Users**: 11 users (1 admin + 10 team members)
- **Projects**: 5 projects
- **Labels**: 15 labels
- **Issues**: 25-40 issues (5-8 per project)
- **Comments**: 50-240 comments (2-6 per issue)
- **Project-User relationships**: Multiple users per project
- **Issue-Label relationships**: 1-4 labels per issue

---

## 12. Common Error Responses

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```
**Solution**: Include valid Bearer token in Authorization header

### 422 Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```
**Solution**: Check request body against validation rules

### 404 Not Found
```json
{
    "message": "Resource not found"
}
```
**Solution**: Verify the resource ID exists

---

## Notes

- All timestamps are in UTC
- Pagination returns 10 items per page by default
- All authenticated routes require `Authorization: Bearer {token}` header
- The `created_by` field for issues is automatically set from the authenticated user
- Project and issue codes are automatically converted to uppercase
- Issue titles are automatically capitalized
