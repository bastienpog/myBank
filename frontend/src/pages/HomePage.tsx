import { useAuthContext } from "../hooks/useAuth";
import { useNavigate } from "react-router";

export default function HomePage() {
  const { user, logout } = useAuthContext();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50">
      <div className="max-w-md w-full space-y-6 p-6 bg-white rounded-lg shadow">
        <div>
          <h1 className="text-2xl font-bold text-brand-dark-deep">Welcome</h1>
          <p className="mt-1 text-sm text-gray-500">
            Logged in as: {user?.email || "Unknown"}
          </p>
        </div>

        <button onClick={handleLogout} className="btn btn-outline w-full">
          Sign Out
        </button>
      </div>
    </div>
  );
}
