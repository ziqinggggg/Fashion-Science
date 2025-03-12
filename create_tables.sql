USE 4602837_fashionscience;

DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS OrderItems;
DROP TABLE IF EXISTS Reviews;
DROP TABLE IF EXISTS CartItems;
DROP TABLE IF EXISTS ShippingInformation;
DROP TABLE IF EXISTS PaymentInformation;
DROP TABLE IF EXISTS Length;
DROP TABLE IF EXISTS SleeveLength;

CREATE TABLE Users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(50) NOT NULL,
    Birthday DATE NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Address VARCHAR(255)
);

-- INSERT INTO Users (Username, Birthday, Email, Password, FirstName, LastName, Address)
-- VALUES
--     ('user123', '1990-01-15', 'user123@email.com', 'password123', 'Jane', 'Doe', '123 Orchard Rd'),
--     ('customer1', '1985-06-10', 'customer1@email.com', 'pass123', 'Alice', 'Smith', '456 Marina Bay'),
--     ('shopper42', '1998-03-21', 'shopper42@email.com', 'secret123', 'Robert', 'Johnson', '789 Johnson St'),
--     ('user456', '1992-11-30', 'user456@email.com', 'password456', 'John', 'Smith', '789 Park Ave'),
--     ('shopper99', '1987-09-05', 'shopper99@email.com', 'mypassword', 'Emily', 'Wilson', '234 Orchard Rd'),
--     ('customer2', '1994-07-19', 'customer2@email.com', 'pass456', 'David', 'Johnson', '567 Marina Bay Rd');

INSERT INTO Users (Username, Birthday, Email, Password, FirstName, LastName, Address)
VALUES
    ('user123', '1990-01-15', 'user123@email.com', '$2y$10$MW4crUlVEzjMU92pKSoJHe2SSWwmL2PSk.O0BEx.ChcvyS25i4Cd.', 'Jane', 'Doe', '123 Orchard Rd'),
    ('customer1', '1985-06-10', 'customer1@email.com', '$2y$10$SFl5fVWUpjaOUl6Y1QkKCeKknyQc0/DivMuRaafxNcGAdXN133qxi', 'Alice', 'Smith', '456 Marina Bay'),
    ('shopper42', '1998-03-21', 'shopper42@email.com', '$2y$10$RwkBXUdXSq4ywXtm0pQq0usZ6/k0/hxDBbMR0gLyagX8pCdHS7fXC', 'Robert', 'Johnson', '789 Johnson St'),
    ('user456', '1992-11-30', 'user456@email.com', '$2y$10$IYqnx4K/7PzAvWbdvoSGVOEPF7ExjMpHC6JkSc3DLyyMYhbeBLnsK', 'John', 'Smith', '789 Park Ave'),
    ('shopper99', '1987-09-05', 'shopper99@email.com', '$2y$10$FTYjddyfVJzHhCuV5JmiduqIN0cPEmDsu3gvMWQuy.DmJgw4AR0uW', 'Emily', 'Wilson', '234 Orchard Rd'),
    ('customer666', '1994-07-19', 'customer666@email.com', '$2y$10$Hr/C.z686BDrj/UuTkLmLuPbJ16zFlA2SvWAgGRGKzwWrayQzotHG', 'David', 'Johnson', '567 Marina Bay Rd');


CREATE TABLE Products (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL,
    CategoryID INT NOT NULL,
    LengthID INT,
    SleeveLengthID INT,
    StockQuantity INT NOT NULL,
    ImageURL VARCHAR(255)
);

INSERT INTO Products (ProductName, Description, Price, CategoryID, LengthID, SleeveLengthID, StockQuantity, ImageURL)
VALUES
    ('Solid Lace Up Cami Top & Skirt', 'A stylish two-piece outfit<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 29.99, 1, 1, 1, 100, 'Product/product1/1.webp'),
    ('Lace Up Front Ruched Bust Asymmetrical Hem 2 In 1 Top', 'A trendy top for women<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 19.99, 2, NULL, 2, 50, 'Product/product2/1.webp'),
    ('Tie Shoulder Frill Trim Cami Dress', 'An elegant cami dress<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 39.99, 3, 2, 1, 75, 'Product/product3/1.webp'),
    ('Solid Lace Up Cami Top & Skirt', 'A stylish two-piece outfit<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 29.99, 1, 1, 1, 100, 'Product/product4/1.webp'),
    ('Solid Ruffle Hem Cami Romper', 'A comfortable dress<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 34.99, 3, 1, 1, 60, 'Product/product5/1.webp'),
    ('Solid Bustier Cami Dress', 'A fashionable dress for women<br>Material: 	Knitted Fabric<br>Composition: 	95% Polyester, 5% Elastane<br>Care Instructions:Machine wash or professional dry clean', 44.99, 3, 1, 1, 80, 'Product/product6/1.webp'),
    ('Solid Wide Straps Top & Mesh Overlay Skirt', 'A stylish two-piece outfit<br>Material:	Woven Fabric<br>Composition: 98% Polyester, 2% Elastane<br>Care Instructions:Machine wash or professional dry clean', 27.99, 1, 1, 1, 90, 'Product/product7/1.webp'),
    ('Lace Up Front Flare Skirt', 'A beautiful flare skirt<br>Material:	Woven Fabric<br>Composition: 	92% Polyester, 8% Viscose<br>Care Instructions:Machine wash or professional dry clean', 22.99, 4, 1, NULL, 55, 'Product/product8/1.webp'),
    ('Lace Up Side Solid Skort', 'A versatile skort for women<br>Material: Fabric<br>Composition: 	95% Polyester, 5% Elastane<br>Care Instructions:Machine wash or professional dry clean', 18.99, 4, 1, NULL, 70, 'Product/product9/1.webp'),
    ('Solid Halter Neck Layer Hem Halter Dress', 'An elegant halter dress<br>Material:	Woven Fabric<br>Composition: 	94% Polyester, 6% Elastane<br>Care Instructions:Machine wash or professional dry clean', 49.99, 3, 1, 1, 65, 'Product/product10/1.webp'),
    ('Solid Zip Back Cami Dress', 'A stylish cami dress<br>Material:	Woven Fabric<br>Composition: 	97% Polyester, 3% Elastane<br>Care Instructions:Machine wash or professional dry clean', 32.99, 3, 1, 1, 85, 'Product/product11/1.webp'),
    ('Square Neck Frill Trim Flounce Sleeve Tie Backless Crop Blouse', 'A trendy blouse for women<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 26.99, 2, NULL, 3, 45, 'Product/product12/1.webp'),
    ('Floral Print Ruched Bust Cami Dress', 'A beautiful floral cami dress<br>Material:	Woven Fabric<br>Composition: 	95% Polyester, 5% Elastane<br>Care Instructions:Machine wash or professional dry clean', 36.99, 3, 1, 1, 70, 'Product/product13/1.webp'),
    ('Frill Trim Tie Front Cami Dress', 'A fashionable cami dress<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 39.99, 3, 2, 1, 60, 'Product/product14/1.webp'),
    ('Lace Up Front Puff Sleeve Frill Trim Crop Top', 'A trendy crop top for women<br>Material:	Woven Fabric<br>Composition: 99% Polyester, 1% Elastane<br>Care Instructions:Machine wash or professional dry clean', 24.99, 2, NULL, 2, 40, 'Product/product15/1.webp'),
    ('Off Shoulder Ruched Mesh Top', 'A stylish mesh top<br>Material: Knitted Fabric<br>Composition: 94% Polyamide, 6% Elastane<br>Care Instructions:Machine wash or professional dry clean', 29.99, 2, NULL, 2, 55, 'Product/product16/1.webp'),
    ('Ditsy Floral Print Puff Sleeve Lace Up Front Blouse', 'A beautiful floral print blouse<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 28.99, 2, NULL, 2, 50, 'Product/product17/1.webp'),
    ('Ditsy Floral Print Tie Shoulder Crop Blouse', 'A trendy crop blouse<br>Material:	Woven Fabric<br>Composition: 95% Polyester, 5% Elastane<br>Care Instructions:Machine wash or professional dry clean', 22.99, 2, NULL, 3, 60, 'Product/product18/1.webp'),
    ('Figure Graphic Puff Sleeve Ruched Bust Tie Backless Dress', 'A unique backless dress<br>Material:	Woven Fabric<br>Composition: 95% Polyester, 5% Elastane<br>Care Instructions:Machine wash or professional dry clean', 46.99, 3, 1, 2, 40, 'Product/product19/1.webp'),
    ('Solid Mesh Overlay Bustier Cami Dress', 'A cute bustier dress<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 42.99, 3, 1, 1, 50, 'Product/product20/1.webp'),
    ('Floral Print Ruffle Hem Mesh Cami Dress', 'A unique pinky dress<br>Material:	Woven Fabric<br>Composition: 100% Polyester<br>Care Instructions:Machine wash or professional dry clean', 37.99, 3, 2, 1, 80, 'Product/product21/1.webp');

CREATE TABLE Categories (
    CategoryID INT PRIMARY KEY AUTO_INCREMENT,
    Category VARCHAR(50) NOT NULL,
    Description TEXT
);

INSERT INTO Categories (Category, Description)
VALUES
    ('Two-piece outfit', 'Coordinated sets for a stylish look'),
    ('Tops', 'Various styles of tops for women'),
    ('Dresses', "A wide range of women's dresses for different occasions"),
    ('Bottoms', 'Bottoms in various designs for women');

CREATE TABLE Length (
    LengthID INT PRIMARY KEY AUTO_INCREMENT,
    LengthName VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO Length (LengthName)
VALUES ('short'), ('long');

CREATE TABLE SleeveLength (
    SleeveLengthID INT PRIMARY KEY AUTO_INCREMENT,
    SleeveType VARCHAR(50) NOT NULL
);

INSERT INTO SleeveLength (SleeveType)
VALUES ('Sleeveless'), ('Short Sleeve'), ('Long Sleeve');

CREATE TABLE Orders (
    OrderID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT NOT NULL,
    OrderDate DATETIME NOT NULL,
    Status VARCHAR(50) NOT NULL,
    TotalAmount DECIMAL(10, 2) NOT NULL
);

INSERT INTO Orders (UserID, OrderDate, Status, TotalAmount)
VALUES
    (1, '2023-10-10 09:30:11', 'Completed', 89.97),
    (2, '2023-10-12 15:45:11', 'Completed', 62.98),
    (2, '2023-10-15 10:15:11', 'Review', 34.99),
    (1, '2023-10-20 16:30:11', 'Delivered', 27.99),
    (3, '2023-10-15 11:45:11', 'Delivered', 39.98),
    (2, '2023-10-25 08:15:11', 'Delivered', 215.94),
    (2, '2023-10-28 14:30:11', 'Shipped', 123.97),
    (2, '2023-10-28 14:30:11', 'Pending', 69.97),
    (6, '2023-10-28 14:30:11', 'Pending', 101.96);
    

CREATE TABLE OrderItems (
    OrderItemID INT PRIMARY KEY AUTO_INCREMENT,
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Size VARCHAR(5)
);

INSERT INTO OrderItems (OrderID, ProductID, Quantity, Price, Size)
VALUES
    (1, 1, 1, 29.99, 'S'),
    (1, 8, 1, 22.99, 'S'),
    (1, 13, 1, 36.99, 'S'),
    (2, 3, 1, 39.99, 'L'),
    (2, 18, 1, 22.99, 'S'),
    (3, 5, 1, 34.99, 'M'),
    (4, 7, 1, 27.99, 'S'),
    (5, 2, 2, 19.99, 'L'),
    (6, 18, 1, 22.99, 'M'),
    (6, 19, 2, 46.99, 'M'),
    (6, 11, 3, 32.99, 'L'),
    (7, 20, 2, 42.99, 'M'),
    (7, 21, 1, 37.99, 'L'),
    (8, 2, 2, 19.99, 'M'),
    (8, 4, 1, 29.99, 'L'),
    (9, 7, 1, 27.99, 'S'),
    (9, 9, 1, 18.99, 'S'),
    (9, 15, 1, 24.99, 'S'),
    (9, 16, 1, 29.99, 'S');

CREATE TABLE Reviews (
    ReviewID INT PRIMARY KEY AUTO_INCREMENT,
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    UserID INT NOT NULL,
    Rating INT(1) NOT NULL,
    Comment TEXT,
    Date DATETIME NOT NULL,
    Photo BLOB
);

INSERT INTO Reviews (ProductID, UserID, Rating, Comment, Date)
VALUES
    (3, 2, 5, 'Love the dress, fits perfectly!', '2023-10-30 12:15:00'),
    (18, 2, 5, 'Perfect skirt for special occasions', '2023-10-30 12:15:00');

CREATE TABLE CartItems (
    CartItemID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    Size VARCHAR(5)
);

INSERT INTO CartItems (UserID, ProductID, Quantity, Size)
VALUES
    (1, 2, 1, 'S'),
    (1, 4, 2, 'XL'),
    (1, 18, 1, 'L'),
    (1, 5, 1, 'M'),
    (1, 7, 4, 'XS'),
    (1, 15, 1, 'S'),
    (2, 19, 2, 'XL'),
    (2, 5, 4, 'S'),
    (2, 11, 1, 'M'),
    (2, 14, 2, 'L'),
    (3, 9, 2, 'M'),
    (3, 13, 1, 'L'),
    (3, 20, 5, 'M'),
    (3, 21, 2, 'XL'),
    (4, 6, 1, 'S'),
    (4, 10, 3, 'XS'),
    (4, 12, 4, 'L'),
    (4, 16, 1, 'S'),
    (5, 1, 3, 'XL'), 
    (5, 3, 2, 'S'),
    (5, 17, 3, 'XL'),
    (5, 8, 2, 'M'),
    (5, 1, 3, 'XL');

CREATE TABLE ShippingInformation (
    ShippingInfoID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT NOT NULL,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    AddressDetails VARCHAR(255),
    City VARCHAR(100) DEFAULT 'Singapore',
    PostalCode INT(6) ZEROFILL NOT NULL,
    ContactNumber INT(8) NOT NULL
);

INSERT INTO ShippingInformation (UserID, FirstName, LastName, Address, PostalCode, ContactNumber)
VALUES
    (1, 'Jane', 'Doe', '123 Orchard Rd','238812', '33336666'),
    (2, 'Alice', 'Smith', '456 Marina Bay Dr', '018930', '78945612');

CREATE TABLE PaymentInformation (
    PaymentInfoID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT NOT NULL,
    CardNumber VARCHAR(16) NOT NULL,
    ExpiryDate VARCHAR(5) NOT NULL,
    SecurityCode INT(3) ZEROFILL NOT NULL,
    FullName VARCHAR(100) NOT NULL
);

INSERT INTO PaymentInformation (UserID, CardNumber, ExpiryDate, SecurityCode, FullName)
VALUES
    (1, '1234567898765432', '12/25', '123', 'Jane Doe'),
    (2, '9876543212345678', '06/24', '456', 'Alice Smith');