const express = require("express");
const bodyParser = require("body-parser");
const mysql = require("mysql");
const cors = require("cors");

const app = express();
const port = 4000;

app.use(cors());

app.use(bodyParser.json());

// // Create a MySQL connection
// const connection = mysql.createConnection({
//   host: "your_database_host",
//   user: "your_username",
//   password: "your_password",
//   database: "your_database_name",
// });

// connection.connect();

// API endpoint to execute SQL queries
app.post("/executeQuery", (req, res) => {
  const { host, username, database, password, query } = req.body;

  console.log(query);

  // Use the input data to create a new connection
  const dynamicConnection = mysql.createConnection({
    host,
    user: username,
    password,
    database,
  });

  dynamicConnection.connect();

  // Execute the SQL query
  dynamicConnection.query(query, (error, results, fields) => {
    if (error) {
      res.status(500).json({ error: error.message });
    } else {
      res.json({ results });
    }

    // Close the dynamic connection
    dynamicConnection.end();
  });
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
