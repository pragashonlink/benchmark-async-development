const express = require("express");
const app = express();
const port = 3000;

app.use(express.json()); // Middleware to parse JSON request bodies

function sleepSync(ms) {
    const start = Date.now();
    while (Date.now() - start < ms);
}

app.get("/health", async (req, res) => {
    res.json({ message: "success" });
});

// Start the server
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
