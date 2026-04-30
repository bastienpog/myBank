import { useState } from "react";
import { Link } from "react-router";
import AuthLayout from "../components/AuthLayout";
import { useRegister } from "../hooks/useAuth";

export default function RegisterPage() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const registerMutation = useRegister();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    registerMutation.mutate({ email, password });
  };

  return (
    <AuthLayout>
      <div className="space-y-6">
        <div>
          <h1 className="text-2xl font-bold text-brand-dark-deep">
            Create Account
          </h1>
          <p className="mt-1 text-sm text-gray-500">Sign up to get started</p>
        </div>

        {registerMutation.isError && (
          <div className="alert alert-error text-sm">
            {(registerMutation.error as Error).message || "Registration failed"}
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
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="••••••••"
              className="input input-bordered w-full"
              required
              minLength={6}
            />
          </div>

          <button
            type="submit"
            className="btn btn-primary w-full"
            disabled={registerMutation.isPending}
          >
            {registerMutation.isPending
              ? "Creating account..."
              : "Create Account"}
          </button>
        </form>

        <p className="text-center text-sm text-gray-500">
          Already have an account?{" "}
          <Link
            to="/login"
            className="text-brand-green hover:underline font-medium"
          >
            Sign in
          </Link>
        </p>
      </div>
    </AuthLayout>
  );
}
