# API Documentation - Aplikasi Menu

## Base URL
```
http://localhost:8000/api
```

## Authentication
Semua endpoint (kecuali login) memerlukan authentication menggunakan Laravel Sanctum.

Header yang diperlukan:
```
Authorization: Bearer {token}
Content-Type: multipart/form-data (untuk upload file)
```

---

## Authentication Endpoints

### Login
**Endpoint:** `POST /login`

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response Success (200):**
```json
{
  "status": "success",
  "message": "Login successful",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com"
  }
}
```

---

### Get Current User
**Endpoint:** `GET /me`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "id": 1,
  "name": "User Name",
  "email": "user@example.com",
  "role_id": 1
}
```

---

## User Endpoints

### Create User
**Endpoint:** `POST /users`

**Headers:**
```
Authorization: Bearer {token}
```

**Middleware:** `Can_create_user`

**Request Body:**
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "Password123",
  "password_confirmation": "Password123",
  "role_id": 2
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `email`: required, valid email, unique
- `password`: required, confirmed, min 8 characters, must contain letters and numbers
- `role_id`: required, integer, must exist in roles table

**Response Success (201):**
```json
{
  "status": "success",
  "message": "User created successfully",
  "data": {
    "id": 2,
    "name": "New User",
    "email": "newuser@example.com",
    "role_id": 2
  }
}
```

---

## Item Endpoints

### Create Item
**Endpoint:** `POST /items`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
name: "Nasi Goreng"
price: 25000
image: [file upload]
```

**Validation Rules:**
- `name`: required, string, max 100 characters
- `price`: required, integer, min 0
- `image`: required, image file (jpeg, png, jpg, gif, webp), max 2MB

**Response Success (201):**
```json
{
  "status": "success",
  "message": "Item created successfully",
  "data": {
    "id": 1,
    "name": "Nasi Goreng",
    "price": 25000,
    "image": "items/abc123.jpg",
    "image_url": "http://localhost:8000/storage/items/abc123.jpg"
  }
}
```

**Response Error (422):**
```json
{
  "message": "The name field is required. (and 1 more error)",
  "errors": {
    "name": ["The name field is required."],
    "image": ["The image field is required."]
  }
}
```

---

### Update Item
**Endpoint:** `PUT /items/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
name: "Nasi Goreng Special" (optional)
price: 30000 (optional)
image: [file upload] (optional)
```

**Validation Rules:**
- `name`: optional, string, max 100 characters
- `price`: optional, integer, min 0
- `image`: optional, image file (jpeg, png, jpg, gif, webp), max 2MB

**Response Success (200):**
```json
{
  "status": "success",
  "message": "Item updated successfully",
  "data": {
    "id": 1,
    "name": "Nasi Goreng Special",
    "price": 30000,
    "image": "items/xyz789.jpg",
    "image_url": "http://localhost:8000/storage/items/xyz789.jpg"
  }
}
```

**Response Error (404):**
```json
{
  "status": "error",
  "message": "Item not found"
}
```

**Note:** Jika image baru diupload, gambar lama akan otomatis dihapus dari storage.

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Error message here"
    ]
  }
}
```

### 500 Internal Server Error
```json
{
  "message": "Server Error"
}
```

---

## Setup Storage

Sebelum menggunakan endpoint items, pastikan sudah menjalankan command berikut untuk membuat symbolic link storage:

```bash
php artisan storage:link
```

Command ini akan membuat symbolic link dari `public/storage` ke `storage/app/public` sehingga file yang diupload bisa diakses secara publik.

---

## Testing dengan Postman/Insomnia

### 1. Login
- Method: POST
- URL: `http://localhost:8000/api/login`
- Body (JSON):
  ```json
  {
    "email": "admin@example.com",
    "password": "password"
  }
  ```
- Copy token dari response

### 2. Create Item
- Method: POST
- URL: `http://localhost:8000/api/items`
- Headers:
  - `Authorization`: `Bearer {token}`
- Body (form-data):
  - `name`: Nasi Goreng
  - `price`: 25000
  - `image`: [pilih file gambar]

### 3. Update Item
- Method: PUT atau POST (dengan `_method=PUT`)
- URL: `http://localhost:8000/api/items/1`
- Headers:
  - `Authorization`: `Bearer {token}`
- Body (form-data):
  - `name`: Nasi Goreng Special (optional)
  - `price`: 30000 (optional)
  - `image`: [pilih file gambar baru] (optional)

**Note:** Beberapa HTTP client tidak support PUT dengan multipart/form-data. Gunakan POST dengan field `_method=PUT` sebagai alternatif.

---

## Database Schema

### Items Table
```sql
- id (bigint, primary key)
- name (varchar 100)
- price (integer)
- image (varchar 255)
- deleted_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Storage Structure
```
storage/
  app/
    public/
      items/
        - abc123.jpg
        - xyz789.png
```

Public access via:
```
public/
  storage/ (symbolic link)
    items/
      - abc123.jpg
      - xyz789.png
```
