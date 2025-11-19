// MongoDB Playground
// Use Ctrl+Space inside a snippet or a string literal to trigger completions.

// The current database to use.
use('SmartMart');

// Create a new document in the collection.
db.getCollection('Orders').insertOne({
  _id: 201,
  customer_id: 101,
  products: [
    { product_id: 1, quantity: 1 },
    { product_id: 5, quantity: 2 }
  ],
  total_amount: 41997,
  status: "delivered",
  notes: "Loyalty discount applied"
});
