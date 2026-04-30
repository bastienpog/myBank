import { Routes, Route, Outlet } from "react-router";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { AuthProvider } from "./contexts/AuthProvider";
import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";
import HomePage from "./pages/HomePage";
import RequireAuth from "./components/RequireAuth";

const queryClient = new QueryClient();

export default function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <Routes>
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />
          <Route
            element={
              <RequireAuth>
                <Outlet />
              </RequireAuth>
            }
          >
            <Route path="/" element={<HomePage />} />
          </Route>
        </Routes>
      </AuthProvider>
    </QueryClientProvider>
  );
}
