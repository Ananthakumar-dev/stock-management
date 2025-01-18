import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import { useState, useEffect } from "react";

const Attributes = ({ errors, setErrors, itemAttributes }) => {
    const [attributes, setAttributes] = useState([]); // Available attributes
    const [selectedAttributes, setSelectedAttributes] = useState([]); // User-selected attributes (existing + new)

    // Fetch data for the item being edited
    useEffect(() => {
        const fetchData = async () => {
            try {
                // Pre-fill attributes from the database
                setSelectedAttributes(
                    itemAttributes.map((attr) => ({
                        id: attr.id, // `item_attributes.id` (from database)
                        attribute_id: attr.attribute_id, // Attribute ID
                        value: attr.value, // Value
                        existing: true, // Mark as existing
                    }))
                );

                // Fetch all attributes from the backend
                const attributesResponse = await axios.get(
                    route("attributes.get")
                );
                setAttributes(attributesResponse.data?.attributes || []);
            } catch (error) {
                console.error("Error fetching attributes", error);
            }
        };

        fetchData();
    }, []);

    const addAttribute = () => {
        // Prevent adding more attributes than available
        if (selectedAttributes.length < attributes.length) {
            setSelectedAttributes([
                ...selectedAttributes,
                { id: null, attribute_id: "", value: "", existing: false }, // New attribute
            ]);
        }
    };

    const removeAttribute = (index) => {
        const updatedAttributes = [...selectedAttributes];
        updatedAttributes.splice(index, 1);
        setSelectedAttributes(updatedAttributes);

        // Clear errors for the removed attribute
        const updatedErrors = { ...errors };
        delete updatedErrors[`attributes.${index}.attribute_id`];
        delete updatedErrors[`attributes.${index}.value`];
        setErrors(updatedErrors);
    };

    const handleAttributeChange = (index, field, value) => {
        // Prevent duplicate attribute selection
        if (field === "id") {
            const isDuplicate = selectedAttributes.some(
                (attr, i) => attr.id === value && i !== index
            );

            if (isDuplicate) {
                setErrors({
                    ...errors,
                    [`attributes.${index}.id`]: [
                        "This attribute is already selected.",
                    ],
                });
                return;
            }
        }

        // Update the attribute's value
        const updatedAttributes = [...selectedAttributes];
        updatedAttributes[index][field] = value;

        // If it's a new attribute, ensure 'id' is set to null for new records
        if (!updatedAttributes[index].id) {
            updatedAttributes[index].id = null;
        }

        setSelectedAttributes(updatedAttributes);
    };

    return (
        <div className="mb-4">
            <InputLabel
                htmlFor="attributes"
                value="Attributes (Optional)"
                className="mb-1"
            />

            {selectedAttributes.map((attr, index) => (
                <div key={index} className="flex items-center mb-2">
                    <div>
                        <input type="hidden" name={`attributes[${index}][id]`} value={attr.id} />

                        {/* Dropdown for selecting attribute */}
                        <select
                            value={attr.attribute_id || ""}
                            onChange={(e) =>
                                handleAttributeChange(
                                    index,
                                    "attribute_id",
                                    e.target.value
                                )
                            }
                            name={`attributes[${index}][attribute_id]`}
                            className={`border ${
                                errors[`attributes.${index}.attribute_id`]
                                    ? "border-red-500"
                                    : "border-gray-300"
                            } rounded px-4 py-2 mr-2`}
                        >
                            <option value="">Select Attribute</option>
                            {attributes.map((attribute) => (
                                <option key={attribute.id} value={attribute.id}>
                                    {attribute.name}
                                </option>
                            ))}
                        </select>

                        {errors[`attributes.${index}.attribute_id`] && (
                            <p className="text-red-500 text-sm">
                                {errors[`attributes.${index}.attribute_id`][0]}
                            </p>
                        )}
                    </div>

                    <div>
                        {/* Input field for attribute value */}
                        <input
                            type="text"
                            value={attr.value || ""}
                            onChange={(e) =>
                                handleAttributeChange(
                                    index,
                                    "value",
                                    e.target.value
                                )
                            }
                            name={`attributes[${index}][value]`}
                            placeholder="Value"
                            className={`border ${
                                errors[`attributes.${index}.value`]
                                    ? "border-red-500"
                                    : "border-gray-300"
                            } rounded px-4 py-2 mr-2`}
                        />

                        {errors[`attributes.${index}.value`] && (
                            <p className="text-red-500 text-sm">
                                {errors[`attributes.${index}.value`][0]}
                            </p>
                        )}
                    </div>

                    {/* Remove button */}
                    <SecondaryButton
                        onClick={() => removeAttribute(index)}
                        type="button"
                    >
                        Remove
                    </SecondaryButton>
                </div>
            ))}

            {/* Add button */}
            {selectedAttributes.length < attributes.length && (
                <PrimaryButton onClick={addAttribute} type="button">
                    Add Attribute
                </PrimaryButton>
            )}
        </div>
    );
};

export default Attributes;
