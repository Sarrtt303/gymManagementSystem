Documentation:-

User Management:

Membership Management:

Attendance Monitoring:

  1. attendance.model.php: It is a representation of the attendance system database schema and provides methods for creating, inserting, and retrieving attendance records. 


  2. attendance.controller.php: Manages the attendance records for the system, allows new attendance entries and fetches attedance logs based on user request. The attendance schema is used for referance to GET, and  POST required information from the database.

   POST:
   Endpoint: /api/v1/attendance
   Request Template:

          {
            "member_id": "123",
            "trainer_id": "456",
            "entry_time": "2024-12-15T09:00:00",
             "exit_time": "2024-12-15T11:00:00"
          }
    GET:
    Endpoint: /api/v1/attendace?member_id=123
    Response Template:

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


Booking sessions: 
   1. booking.model.php: Schema for seamless creation of data in the database
   2. booking.controller.php: Handles booking operations for a system where users can book, view, or cancel session bookings. It interacts with the BookingSchema model and handles different HTTP request methods to perform specific actions
       POST: 
       


       GET:

Goal Setting and progress tracker:

Reports and metrics:

Notifications and Alerts:

Admin Dashboard:



