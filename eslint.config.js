import js from "@eslint/js";
import globals from "globals";
import { defineConfig } from "eslint/config";
import html from "eslint-plugin-html"; // ✅ Import the plugin


export default defineConfig([
  { files: ["**/*.{js,mjs,cjs}"], plugins: { js }, extends: ["js/recommended"] },
  { files: ["**/*.js"], languageOptions: { sourceType: "script" } },
  { files: ["**/*.{js,mjs,cjs}"], languageOptions: { globals: globals.browser } },
  {
    // ✅ Add this block to support <script> tags in blade or html
    files: ["**/*.{php,blade.php,html}"],
    plugins: { html },
    processor: html.processors.html,
  },
]);