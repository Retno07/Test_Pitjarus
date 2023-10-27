const express = require('express');
const bodyParser = require('body-parser');
const koneksi = require('./config/database');
const app = express();
const PORT = process.env.PORT || 5000;
// set body parser
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));

// read data
app.get('/api/getChart', (req, res) => {
    
     // buat query sql
     const querySql = "WITH compliance AS (SELECT d.area_id,d.area_name,sum(a.compliance) AS compliance FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id GROUP BY d.area_id), region AS (SELECT compliance,(SELECT SUM(compliance) FROM report_product) AS all_region FROM compliance), hasil AS (SELECT (compliance / all_region * 100) AS hasil FROM region) SELECT CONVERT(hasil,DECIMAL(20,2)) as total FROM hasil";
     console.log(querySql);
     
     
     // jalankan query
     koneksi.query(querySql, (err, rows, field) => {
         // error handling
         if (err) {
             return res.status(500).json({ message: 'Ada kesalahan', error: err });
         }
 
         res.header('Access-Control-Allow-Origin', '*');
         // jika request berhasil
         res.status(200).json({ data: rows }); 
     });
 }); 

 app.get('/api/getTable', (req, res) => {

     // buat query sql
     const querySql2 = 'WITH dki AS ( SELECT e.brand_name,sum(a.compliance) AS jakarta FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 1 GROUP BY e.brand_id ), jabar AS ( SELECT e.brand_name,sum(a.compliance) AS jabar FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 2 GROUP BY e.brand_id ), kalimantan AS ( SELECT e.brand_name,sum(a.compliance) AS kalimantan FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 3 GROUP BY e.brand_id ), jateng AS ( SELECT e.brand_name,sum(a.compliance) AS jateng FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 4 GROUP BY e.brand_id ), bali AS ( SELECT e.brand_name,sum(a.compliance) AS bali FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 5 GROUP BY e.brand_id ) SELECT a.brand_name,a.jakarta,b.jabar,c.kalimantan,d.jateng,e.bali FROM dki a JOIN jabar b ON a.brand_name = b.brand_name JOIN kalimantan c ON a.brand_name = c.brand_name JOIN jateng d ON a.brand_name = d.brand_name JOIN bali e ON a.brand_name = e.brand_name';
     // jalankan query
     koneksi.query(querySql2, (err, rows, field) => {
         // error handling
         if (err) {
             return res.status(500).json({ message: 'Ada kesalahan', error: err });
         }

         res.header('Access-Control-Allow-Origin', '*');
         // jika request berhasil
         res.status(200).json({ data: rows });
     });
 });
 app.get('/api/UpdateChart', (req, res) => {

    // const { start_date, end_date } = req.query;
    const startDate = req.query.start_date;
    const endDate = req.query.end_date;
    // console.log("Start Date: ", startDate);
    // console.log("End Date: ", endDate);
     // buat query sql
     const querySql = "WITH compliance AS (SELECT a.tanggal, d.area_id,d.area_name,sum(a.compliance) AS compliance FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id WHERE tanggal BETWEEN '" + startDate + "' AND '" + endDate + "' GROUP BY d.area_id), region AS (SELECT tanggal, compliance,(SELECT SUM(compliance) FROM report_product) AS all_region FROM compliance), hasil AS (SELECT (compliance / all_region * 100) AS hasil FROM region) SELECT CONVERT(hasil,DECIMAL(20,2)) as total FROM hasil";
    //  console.log(querySql);
     
     
     // jalankan query
     koneksi.query(querySql, (err, rows, field) => {
         // error handling
         if (err) {
             return res.status(500).json({ message: 'Ada kesalahan', error: err });
         }
 
         res.header('Access-Control-Allow-Origin', '*');
         // jika request berhasil
         res.status(200).json({ data: rows }); 
     });
 }); 

 app.get('/api/UpdateTable', (req, res) => {

    const startDate = req.query.start_date;
    const endDate = req.query.end_date;
    // console.log("Start Date: ", startDate);
    // console.log("End Date: ", endDate);
    // buat query sql
    const querySql2 = "WITH dki AS ( SELECT a.tanggal, e.brand_name,sum(a.compliance) AS jakarta FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 1 AND tanggal BETWEEN '" + startDate + "' AND '" + endDate + "' GROUP BY e.brand_id ), jabar AS ( SELECT e.brand_name,sum(a.compliance) AS jabar FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 2 AND tanggal BETWEEN '" + startDate + "' AND '" + endDate + "' GROUP BY e.brand_id ), kalimantan AS ( SELECT e.brand_name,sum(a.compliance) AS kalimantan FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 3 AND tanggal BETWEEN '" + startDate + "' AND '" + endDate + "' GROUP BY e.brand_id ), jateng AS ( SELECT e.brand_name,sum(a.compliance) AS jateng FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 4 AND tanggal BETWEEN '" + startDate + "' AND '" + endDate + "' GROUP BY e.brand_id ), bali AS ( SELECT e.brand_name,sum(a.compliance) AS bali FROM report_product a JOIN store b ON a.store_id = b.store_id JOIN product c ON a.product_id = c.product_id JOIN store_area d ON b.area_id = d.area_id JOIN product_brand e ON c.brand_id = e.brand_id WHERE d.area_id = 5 AND tanggal BETWEEN '" + startDate + "' AND '" + endDate + "' GROUP BY e.brand_id ) SELECT a.brand_name,a.jakarta,b.jabar,c.kalimantan,d.jateng,e.bali FROM dki a JOIN jabar b ON a.brand_name = b.brand_name JOIN kalimantan c ON a.brand_name = c.brand_name JOIN jateng d ON a.brand_name = d.brand_name JOIN bali e ON a.brand_name = e.brand_name";
    // console.log(querySql2);
    // jalankan query
    koneksi.query(querySql2, (err, rows, field) => {
        // error handling
        if (err) {
            return res.status(500).json({ message: 'Ada kesalahan', error: err });
        }

        res.header('Access-Control-Allow-Origin', '*');
        // jika request berhasil
        res.status(200).json({ data: rows });
    });
});
// buat server nya
app.listen(PORT, () => console.log(`Server running at port: ${PORT}`));