/**
 * @file sample_data.sql
 * 
 * @brief Provides sample data for the database.
 * 
 * @author David Petrovski
 * @author Isabelle Coletti
 * @author Amanda Zedwick
 * 
 * CSCI 466 - 1
 */


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Dog Food',
	' ',
	'25.50',
	'500',
	'https://images2.imgbox.com/e7/74/OhlW7DXn_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Food Bowl',
	' ',
	'7.00',
	'250',
	'https://images2.imgbox.com/02/fb/bqz3I1Jp_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'A Very Special Shampoo',
	' ',
	'21.55',
	'250',
	'https://images2.imgbox.com/f1/d9/CyLrmKd2_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(	
	'Collar (Small)',
	' ',
	'8',
	'300',
	'https://images2.imgbox.com/6f/e9/2nuYpCIw_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Collar (Large)',
	' ',
	'10',
	'300',
	'https://images2.imgbox.com/b3/07/2IjAqifZ_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(	
	'Leash',
	' ',
	'12.99',
	'400',
	'https://images2.imgbox.com/0f/e3/T4jkbFKB_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(	
	'Food Mat',
	' ',
	'28.99',
	'429',
	'https://images2.imgbox.com/52/f2/3TxdsyIy_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Pet Bed (Small)',
	' ',
	'99.99',
	'300',
	'https://images2.imgbox.com/6d/e4/iI6K6W96_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(	
	'Pet Bed (Large)',
	' ',
	'150.99',
	'350',
	'https://images2.imgbox.com/9b/e8/m8Ib3y5E_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Brush',
	' ',
	'9.99',
	'400',
	'https://images2.imgbox.com/98/af/LzKtEsvQ_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Pet Cologne',
	' ',
	'20.99',
	'500',
	'https://images2.imgbox.com/c9/2d/KgMapJ2g_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Pet Perfume',
	' ',
	'25.99',
	'400',
	'https://images2.imgbox.com/1d/89/oRhBNPYl_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Logo Pet Hoodie (Small)',
	' ',
	'30.50',
	'100',
	'https://images2.imgbox.com/9a/d4/FpheE4Yi_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(	
	'Logo Pet Hoodie (Large)',
	' ',
	'35.50',
	'150',
	'https://images2.imgbox.com/bb/73/qgLXC5PE_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(	
	'Hot Dog Toy (Squeaky)',
	' ',
	'69.99',
	'600',
	'https://images2.imgbox.com/40/05/HArcYloD_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(	
	'Tarantula',
	' ',
	'150.00',
	'10',
	'https://images2.imgbox.com/4a/27/7SdP91xU_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Chinchilla',
	' ',
	'200.00',
	'12',
	'https://images2.imgbox.com/75/94/xdIsSm6a_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Baby Fox',
	' ',
	'499.99',
	'13',
	'https://images2.imgbox.com/84/1b/TpaehIoJ_o.png' 
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(
	'Chameleon',
	' ',
	'299.99',
	'5',
	'https://images2.imgbox.com/87/94/2hgIpDhh_o.png'
);


INSERT INTO Products
(Name, Description, Price, Quantity, ImgLink)
VALUES
(	
	'Bearded Dragon (Bob-Made)',
	' ',
	'999.99',
	'6',
	'https://images2.imgbox.com/eb/03/cR7fFMle_o.png' 
);


INSERT INTO Employees
VALUES
(
	'admin',
	'admin',
	'admin'
);


INSERT INTO Employees
VALUES
(
	'1900409',
	'Isabelle Coletti',
	'pink'
);


INSERT INTO Employees
VALUES
(
	'1894079',
	'David Petrovski',
	'SlowR6'
);


INSERT INTO Employees
VALUES
(
	'1866286',
	'Amanda Zedwick',
	'fox'
);


INSERT INTO Customers
VALUES
(
	'admin',
	'admin',
	'admin',
	'admin@niu.cs.edu'
);


INSERT INTO Customers
VALUES
(
	'petrovskidavid',
	'SlowR6',
	'David Petrovski',
	'petrovskidavid1@gmail.com'
);


INSERT INTO Orders 
(EmpID, Username)
VALUES
(
	'admin',
	'admin'
);