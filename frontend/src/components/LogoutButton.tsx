import { useLogout } from "../hooks/useAuth";

export default function LogoutButton() {
  const logout = useLogout();

  return (
    <button
      onClick={logout}
      className="btn btn-ghost btn-sm"
      type="button"
    >
      Logout
    </button>
  );
}
