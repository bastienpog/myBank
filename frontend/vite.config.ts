import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";
import { defineConfig } from "vitest/config";

// https://vite.dev/config/
export default defineConfig({
  plugins: [react(), tailwindcss()],
  server: {
    host: "0.0.0.0", // ← this line is required for Docker
    port: 5173,
    strictPort: true,
  },
  test: {
    environment: "jsdom",
    globals: true,
    setupFiles: ["./src/setupTests.ts"],
  },
});
