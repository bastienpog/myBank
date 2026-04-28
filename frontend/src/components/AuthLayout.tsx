import type { ReactNode } from "react";

interface AuthLayoutProps {
  children: ReactNode;
}

export default function AuthLayout({ children }: AuthLayoutProps) {
  return (
    <div className="min-h-screen flex items-center justify-center bg-[var(--color-base-100)] px-4">
      <div className="w-full max-w-md">
        <div className="h-2 bg-brand-gradient rounded-t-xl" />
        <div className="bg-white p-8 rounded-b-xl shadow-lg">
          {children}
        </div>
      </div>
    </div>
  );
}
