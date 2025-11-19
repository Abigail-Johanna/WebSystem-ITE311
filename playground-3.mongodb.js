// MongoDB Playground
// Use Ctrl+Space inside a snippet or a string literal to trigger completions.

// The current database to use.
use('SmartMart');

// Create a new document in the collection.
db.Products.insertMany([
  {
    _id: 1,
    product_name: "Acer Aspire 5",
    category: "Laptops",
    price: 35999,
    stock_quantity: 5,
    tags: ["intel i5", "15.6 inch", "best seller"]
  },
  {
    _id: 2,
    product_name: "Logitech G502 Hero",
    category: "Accessories",
    price: 2999,
    stock_quantity: 0, // Out of stock
    tags: ["gaming", "wired", "precision"]
  },
  {
    _id: 3,
    product_name: "Razer BlackShark V2",
    category: "Headphones",
    price: 4999,
    stock_quantity: 10,
    tags: ["gaming", "wired", "surround sound"]
  },
  {
    _id: 4,
    product_name: "Samsung Galaxy Tab S6 Lite",
    category: "Tablets",
    price: 17999,
    stock_quantity: 3,
    tags: ["android", "new arrival"]
  },
  {
    _id: 5,
    product_name: "Kingston 500GB SSD",
    category: "Storage",
    price: 2999,
    stock_quantity: 8,
    tags: ["fast", "reliable", "on sale"]
  }
])
