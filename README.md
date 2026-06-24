[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/OFWe9D1G)
# StyleLoop ClothingStore POE Project

## Software Required
- XAMPP Control Panel
- Apache
- MySQL
- Web browser such as Chrome or Edge
- VS Code or any code editor, if the marker wants to view the code

## How to Open and Run the Project
1. Extract the `StyleLoop` folder.
2. Copy the `StyleLoop` folder to `C:\xampp\htdocs\`.
3. Start Apache and MySQL in XAMPP.
4. Open this URL first to create the database and load sample data:
   `http://localhost/StyleLoop/loadClothingStore.php`
5. Then open the website:
   `http://localhost/StyleLoop/index.php`

## Database Setup
The database is created automatically by running `loadClothingStore.php`.
The database name is `ClothingStore`.

## Database File / SQL Script Location
The SQL script is included in the project folder as:
`myClothingStore.sql`

The setup script that creates and loads the database is:
`loadClothingStore.php`

## Testing Login Details
### Admin
Email: `admin@styleloop.co.za`
Password: `admin123`

### Verified User
Username: `john`
Email: `john@gmail.com`
Password: `user123`

## Important Notes for the Marker
- Run `loadClothingStore.php` before testing the website.
- New users must be approved by the administrator before they can log in.
- Sellers can submit clothing requests with brand, description, image, category, price and quantity.
- Admin can approve, update and delete clothing and users.
- The shopping cart supports AddItem, RemoveItem, EmptyCart, Checkout, Login and ProcessInput functions through `CartClass.php`.
- If the same item is added again, the cart quantity increases.
- Checkout displays a reference number, writes order details to the database, creates order line records, decrements item quantity, and clears the cart.
- Purchase history and admin reports are included.
