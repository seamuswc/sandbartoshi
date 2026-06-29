import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const filePath = path.join(__dirname, '..', 'data', 'properties.json');

export function loadProperties() {
  const raw = fs.readFileSync(filePath, 'utf8');
  return JSON.parse(raw);
}
