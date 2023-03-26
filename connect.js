function connect() {
    const mysql = require('mysql');
    // Connect
    const connection = mysql.createConnection({
        host     : 'mysql01.cs.virginia.edu',
        user     : 'al4ne',
        password : 'cs4750',
        database : 'al4e_d'
      });
    connection.connect(function(err) {
    if (err) throw err;
    
    // Log connection to console 
    console.log('Connected!');
    });
    // Test query 
    connection.query('SELECT * FROM In_shopping_cart', function (err, result, fields) {
    if (err) throw err;
    console.log(result);
    });
}

// Set this to run upon window load
window.onload = connect; 