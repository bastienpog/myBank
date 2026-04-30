const API_BASE = "/api";

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterCredentials {
  email: string;
  password: string;
}

export interface User {
  id: number;
  email: string;
}

export interface AuthResponse {
  token: string;
  user?: User;
}

export interface RegisterResponse {
  user: User;
}

async function fetcher<T>(url: string, options?: RequestInit): Promise<T> {
  const token = localStorage.getItem("authToken");
  const res = await fetch(`${API_BASE}${url}`, {
    ...options,
    headers: {
      "Content-Type": "application/json",
      ...(token && { Authorization: `Bearer ${token}` }),
      ...options?.headers,
    },
  });

  if (!res.ok) {
    const error = await res.json().catch(() => ({}));
    throw new Error(error.message || error.error || `HTTP ${res.status}`);
  }

  if (res.status === 204) {
    return {} as T;
  }

  return res.json();
}

export const authService = {
  login: async (credentials: LoginCredentials): Promise<AuthResponse> => {
    return fetcher<AuthResponse>("/auth/login", {
      method: "POST",
      body: JSON.stringify(credentials),
    });
  },

  register: async (credentials: RegisterCredentials): Promise<RegisterResponse> => {
    return fetcher<RegisterResponse>("/auth/register", {
      method: "POST",
      body: JSON.stringify(credentials),
    });
  },

  getCurrentUser: async (): Promise<User> => {
    return fetcher<User>("/auth/me");
  },

  logout: (): void => {
    localStorage.removeItem("authToken");
  },

  getToken: (): string | null => localStorage.getItem("authToken"),

  setToken: (token: string): void => localStorage.setItem("authToken", token),

  fetcher,
};
