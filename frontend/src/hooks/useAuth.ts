import { useContext } from "react";
import { AuthContext } from "../contexts/AuthContext";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { authService } from "../services/authService";
import { useNavigate } from "react-router";
import type { User } from "../lib/types";

export const useAuthContext = () => {
  const context = useContext(AuthContext);
  if (!context) throw new Error("useAuthContext must be used within AuthProvider");
  return context;
};

export const useLogin = () => {
  const { login } = useAuthContext();
  const navigate = useNavigate();

  return useMutation({
    mutationFn: authService.login,
    onSuccess: async (data) => {
      await login(data.token);
      navigate("/");
    },
    onError: (error: Error) => {
      console.error("Login error:", error.message);
    },
  });
};

export const useRegister = () => {
  const navigate = useNavigate();

  return useMutation({
    mutationFn: authService.register,
    onSuccess: () => {
      navigate("/login");
    },
    onError: (error: Error) => {
      console.error("Register error:", error.message);
    },
  });
};

export const useCurrentUser = () => {
  return useQuery<User>({
    queryKey: ["auth", "me"],
    queryFn: authService.getCurrentUser,
    retry: false,
  });
};

export const useLogout = () => {
  const { logout } = useAuthContext();
  const queryClient = useQueryClient();
  const navigate = useNavigate();

  return () => {
    logout();
    queryClient.clear();
    navigate("/login");
  };
};
