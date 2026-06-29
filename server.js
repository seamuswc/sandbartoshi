import 'dotenv/config';
import express from 'express';
import path from 'path';
import { fileURLToPath } from 'url';
import { loadProperties } from './lib/properties.js';
import { renderMapPage } from './templates/map.js';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const app = express();
const PORT = Number(process.env.PORT) || 3000;
const GOOGLE_MAPS_API_KEY =
  process.env.GOOGLE_MAPS_API_KEY || 'AIzaSyBt9g5VppVNA7VoBB5SgoPwnMQtP6-_Cgk';

app.use(express.static(path.join(__dirname, 'public')));

app.get('/', (req, res) => {
  const properties = loadProperties();
  res.type('html').send(renderMapPage(properties, GOOGLE_MAPS_API_KEY));
});

app.listen(PORT, () => {
  console.log(`Sandbartoshi running at http://localhost:${PORT}`);
});
