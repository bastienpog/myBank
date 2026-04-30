import { Navigate, Outlet } from "react-router";
import { useAuthContext } from "../hooks/useAuth";

export default function RequireAuth({ children }: { children?: React.ReactNode }) {
  const { user, isLoading } = useAuthContext();

  if (isLoading) {
    return <div className="min-h-screen flex items-center justify-center">Loading...</div>;
  }

  if (!user) {
    return <Navigate to="/login" replace />;
  }

  return <>{children ? children : <Outlet context={{ user }} />}</>;
}
