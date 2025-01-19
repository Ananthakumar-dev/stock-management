import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { router } from "@inertiajs/react";
import { useState } from "react";

const Filter = ({ initialSearch }) => {
    const [search, setSearch] = useState(initialSearch || "");

    const handleFilter = (e) => {
        e.preventDefault(); // Prevent default form submission behavior

        router.get(route("measurements.index"), { search }, { replace: true }); // Redirect with search parameter
    };

    return (
        <form onSubmit={handleFilter} className="flex gap-1 items-center">
            <TextInput
                type="text"
                placeholder="Name"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
            />

            <PrimaryButton type="submit">Filter</PrimaryButton>
        </form>
    );
};

export default Filter;
