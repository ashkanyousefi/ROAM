CREATE DATABASE IF NOT EXISTS cs6400_su19_team029;
USE cs6400_su19_team029;

-- Tables

CREATE TABLE User (
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE Owner (
  username varchar(50) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE Manager (
  username varchar(50) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE Salesperson (
  username varchar(50) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE InventoryClerk (
  username varchar(50) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE Vehicle (
  VIN char(17) NOT NULL,
  mileage int(16) unsigned NOT NULL,
  model_name varchar(50) NOT NULL,
  model_year char(4) NOT NULL,
  vehicle_condition varchar(50) NOT NULL,
  description text DEFAULT NULL,
  type varchar(50) NOT NULL,
  manufacturer varchar(50) NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE VehicleType (
  type varchar(50) NOT NULL,
  PRIMARY KEY (type)
);

CREATE TABLE Manufacturer (
  name varchar(50) NOT NULL,
  PRIMARY KEY (name)
);

CREATE TABLE VehicleColor (
  VIN char(17) NOT NULL,
  color varchar(50) NOT NULL,
  PRIMARY KEY (VIN, color)
);

CREATE TABLE Customer (
  customerID varchar(50) NOT NULL,
  email varchar(50) DEFAULT NULL,
  street varchar(50) NOT NULL,
  city varchar(50) NOT NULL,
  state varchar(50) NOT NULL,
  zip varchar(50) NOT NULL,
  phone varchar(50) NOT NULL,
  PRIMARY KEY (customerID)
);

CREATE TABLE Individual (
  DLNumber varchar(50) NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  PRIMARY KEY (DLNumber)
);

CREATE TABLE Business (
  TaxID varchar(50) NOT NULL,
  business_name varchar(50) NOT NULL,
  primary_contact_name varchar(50) NOT NULL,
  primary_contact_title varchar(50) NOT NULL,
  PRIMARY KEY (TaxID)
);

CREATE TABLE SalesTransaction (
  VIN char(17) NOT NULL,
  customerID varchar(50) NOT NULL,
  salesperson varchar(50) NOT NULL,
  sales_date date NOT NULL,
  sales_price float NOT NULL,
  UNIQUE (VIN, customerID, salesperson)
);

CREATE TABLE PurchaseTransaction (
  VIN char(17) NOT NULL,
  customerID varchar(50) NOT NULL,
  inventory_clerk varchar(50) NOT NULL,
  purchase_date date NOT NULL,
  purchase_price float NOT NULL,
  UNIQUE (VIN, customerID, inventory_clerk)
);

CREATE TABLE Repair (
  VIN char(17) NOT NULL,
  vendor_name varchar(50) NOT NULL,
  recall_number varchar(50) DEFAULT NULL,
  salesperson varchar(50) NOT NULL,
  start_date date DEFAULT NULL,
  end_date date DEFAULT NULL,
  total_cost float NOT NULL,
  status varchar(50) NOT NULL,
  description text DEFAULT NULL
);

CREATE TABLE Recall (
  recall_number varchar(50) NOT NULL,
  description text NOT NULL,
  associated_manufacturer varchar(50) NOT NULL,
  PRIMARY KEY (recall_number)
);

CREATE TABLE Vendor (
  vendor_name varchar(50) NOT NULL,
  phone varchar(50) NOT NULL,
  street varchar(50) NOT NULL,
  city varchar(50) NOT NULL,
  state varchar(50) NOT NULL,
  zip varchar(50) NOT NULL,
  PRIMARY KEY (vendor_name)
);

-- Constraints   Foreign Keys: FK_ChildTable_childColumn_ParentTable_parentColumn

ALTER TABLE Owner
  ADD CONSTRAINT fk_Owner_username_User_username FOREIGN KEY (username) REFERENCES `User` (username);

ALTER TABLE Manager
  ADD CONSTRAINT fk_Manager_username_User_username FOREIGN KEY (username) REFERENCES `User` (username);

ALTER TABLE Salesperson
  ADD CONSTRAINT fk_Salesperson_username_User_username FOREIGN KEY (username) REFERENCES `User` (username);

ALTER TABLE InventoryClerk
  ADD CONSTRAINT fk_InventoryClerk_username_User_username FOREIGN KEY (username) REFERENCES `User` (username);

ALTER TABLE Vehicle
  ADD CONSTRAINT fk_Vehicle_type_VehicleType_type FOREIGN KEY (type) REFERENCES VehicleType (type),
  ADD CONSTRAINT fk_Vehicle_manufacturer_Manufacturer_name FOREIGN KEY (manufacturer) REFERENCES Manufacturer (name);

ALTER TABLE VehicleColor
  ADD CONSTRAINT fk_VehicleColor_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);

ALTER TABLE Individual
  ADD CONSTRAINT fk_Individual_DLNumber_Customer_customerID FOREIGN KEY (DLNumber) REFERENCES Customer (customerID);

ALTER TABLE Business
  ADD CONSTRAINT fk_Business_TaxID_Customer_customerID FOREIGN KEY (TaxID) REFERENCES Customer (customerID);

ALTER TABLE SalesTransaction
  ADD CONSTRAINT fk_Sale_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN),
  ADD CONSTRAINT fk_Sale_customerID_Customer_customerID FOREIGN KEY (customerID) REFERENCES Customer (customerID),
  ADD CONSTRAINT fk_Sales_salesperson_Salesperson_username FOREIGN KEY (salesperson) REFERENCES Salesperson (username);

ALTER TABLE PurchaseTransaction
  ADD CONSTRAINT fk_Purchase_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN),
  ADD CONSTRAINT fk_Purchase_customerID_Customer_customerID FOREIGN KEY (customerID) REFERENCES Customer (customerID),
  ADD CONSTRAINT fk_Purchase_inventory_clerk_InventoryClerk_username FOREIGN KEY (inventory_clerk) REFERENCES InventoryClerk (username);

ALTER TABLE Repair
  ADD CONSTRAINT fk_Repair_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN),
  ADD CONSTRAINT fk_Repair_recall_number_Recall_recall_number FOREIGN KEY (recall_number) REFERENCES Recall (recall_number),
  ADD CONSTRAINT fk_Repair_vendor_name_Vendor_vendor_name FOREIGN KEY (vendor_name) REFERENCES Vendor (vendor_name);

ALTER TABLE Recall
  ADD CONSTRAINT fk_Recall_manufacturer_Manufacturer_name FOREIGN KEY (associated_manufacturer) REFERENCES Manufacturer (name);
