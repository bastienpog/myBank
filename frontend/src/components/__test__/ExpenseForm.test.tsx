import { render, screen } from "@testing-library/react";
import { describe, it, expect } from "vitest";
import ExpenseForm from "../ExpenseForm";
import "@testing-library/jest-dom";

describe("ExpenseForm — affichage", () => {
  it("affiche les champs du formulaire", () => {
    render(<ExpenseForm />);

    // Le champ montant est présent et accessible via son label
    expect(screen.getByLabelText(/amount/i)).toBeInTheDocument();

    // Le bouton de soumission est présent
    expect(screen.getByRole("button", { name: /add/i })).toBeInTheDocument();
  });
});
