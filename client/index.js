const express = require("express");
const app = express();
const port = 3000;

app.use(express.json()); // Middleware to parse JSON request bodies

function sleepSync(ms) {
    const start = Date.now();
    while (Date.now() - start < ms);
}

app.get("/health", async (req, res) => {
    sleepSync(30000);
    res.json({ message: "This will block the entire server for 30 seconds!" });
});

// Start the server
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
