import { useState, useEffect, ReactNode, useCallback } from "react";
import { AuthContext } from "./AuthContext";
import { authService } from "../services/authService";
import type { User } from "../lib/types";

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setTokenState] = useState<string | null>(authService.getToken());
  const [isLoading, setIsLoading] = useState<boolean>(!!authService.getToken());

  const login = async (newToken: string) => {
    authService.setToken(newToken);
    setTokenState(newToken);
    try {
      const userData = await authService.getCurrentUser();
      setUser(userData);
    } catch {
      authService.logout();
      setTokenState(null);
    }
  };

  const logout = () => {
    authService.logout();
    setTokenState(null);
    setUser(null);
  };

  const checkAuth = useCallback(async () => {
    const currentToken = authService.getToken();
    if (!currentToken) {
      setIsLoading(false);
      return;
    }

    try {
      const userData = await authService.getCurrentUser();
      setUser(userData);
    } catch {
      authService.logout();
      setTokenState(null);
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => {
    checkAuth();
  }, [checkAuth]);

  return (
    <AuthContext.Provider value={{ user, token, isLoading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
