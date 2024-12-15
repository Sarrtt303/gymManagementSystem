# Project Documentation

## User Management

Details coming soon...

## Membership Management

Details coming soon...

## Attendance Monitoring

### Files

1. **`attendance.model.php`**
   - Represents the attendance system database schema.
   - Provides methods for creating, inserting, and retrieving attendance records.

2. **`attendance.controller.php`**
   - Manages attendance records for the system.
   - Allows new attendance entries and fetches attendance logs based on user requests.
   - Utilizes the attendance schema to process `GET` and `POST` requests for database operations.

### Endpoints

#### **POST**
- **Endpoint**: `/api/v1/attendance`
- **Request Template**:
  ```json
  {
    "member_id": "123",
    "trainer_id": "456",
    "entry_time": "2024-12-15T09:00:00",
    "exit_time": "2024-12-15T11:00:00"
  }
  ```

#### **GET**
- **Endpoint**: `/api/v1/attendance?member_id=123`
- **Response Template**:
  ```json
  [
    {
      "attendance_id": 1,
      "member_id": 123,
      "trainer_id": 456,
      "entry_time": "2024-12-15T09:00:00",
      "exit_time": "2024-12-15T11:00:00"
    },
    {
      "attendance_id": 2,
      "member_id": 123,
      "trainer_id": null,
      "entry_time": "2024-12-14T10:00:00",
      "exit_time": "2024-12-14T12:00:00"
    }
  ]
  ```

## Booking Sessions

### Files

1. **`booking.model.php`**
   - Defines the schema for seamless creation of booking data in the database.

2. **`booking.controller.php`**
   - Handles booking operations, allowing users to book, view, or cancel session bookings.
   - Interacts with the `BookingSchema` model.
   - Processes various HTTP requests.

### Endpoints

#### **POST**
- **Details coming soon...**

#### **GET**
- **Details coming soon...**

## Goal Setting and Progress Tracker

Details coming soon...

## Reports and Metrics

Details coming soon...

## Notifications and Alerts

Details coming soon...

## Admin Dashboard

Details coming soon...

