import { useState } from "react";
import { Link } from "react-router";
import AuthLayout from "../components/AuthLayout";
import { useLogin } from "../hooks/useAuth";

export default function LoginPage() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const loginMutation = useLogin();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    loginMutation.mutate({ email, password });
  };

  return (
    <AuthLayout>
      <div className="space-y-6">
        <div>
          <h1 className="text-2xl font-bold text-brand-dark-deep">Welcome</h1>
          <p className="mt-1 text-sm text-gray-500">Sign in to your account</p>
        </div>

        {loginMutation.isError && (
          <div className="alert alert-error text-sm">
            {(loginMutation.error as Error).message || "Login failed"}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium mb-1">Email</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="you@example.com"
              className="input input-bordered w-full"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-medium mb-1">Password</label>
            <div className="relative">
              <input
                type={showPassword ? "text" : "password"}
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="input input-bordered w-full pr-10"
                required
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"
              >
                {showPassword ? "Hide" : "Show"}
              </button>
            </div>
          </div>

          <div className="flex items-center justify-between">
            <a href="#" className="text-sm text-brand-dark hover:underline">
              Forgot password?
            </a>
          </div>

          <button
            type="submit"
            className="btn btn-primary w-full"
            disabled={loginMutation.isPending}
          >
            {loginMutation.isPending ? "Signing in..." : "Sign In"}
          </button>
        </form>

        <p className="text-center text-sm text-gray-500">
          Don't have an account?{" "}
          <Link
            to="/register"
            className="text-brand-green hover:underline font-medium"
          >
            Sign up
          </Link>
        </p>
      </div>
    </AuthLayout>
  );
}
