import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import { useState, useEffect } from "react";

const Attributes = ({ errors, setErrors }) => {
    const [attributes, setAttributes] = useState([]);
    const [selectedAttributes, setSelectedAttributes] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            const getAttributes = route("attributes.get");
            const attributesResponse = await axios.get(getAttributes);

            setAttributes(attributesResponse.data?.attributes);
        };
        fetchData();
    }, []);

    const addAttribute = () => {
        setSelectedAttributes([...selectedAttributes, { id: "", value: "" }]);
    };

    const removeAttribute = (index) => {
        const updatedAttributes = [...selectedAttributes];
        updatedAttributes.splice(index, 1);
        setSelectedAttributes(updatedAttributes);
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

        // Clear errors for the current field if fixed
        if (errors[`attributes.${index}.${field}`]) {
            const updatedErrors = { ...errors };
            delete updatedErrors[`attributes.${index}.${field}`];
            setErrors(updatedErrors);
        }

        // Update attributes
        const updatedAttributes = [...selectedAttributes];
        updatedAttributes[index][field] = value;
        setSelectedAttributes(updatedAttributes);
    };

    return (
        <div className="mb-4">
            <InputLabel htmlFor="attributes" value="Attributes (Optional)" className="mb-1" />

            {selectedAttributes.map((attr, index) => (
                <div key={index} className="flex items-center mb-2">
                    <div>
                        <select
                            value={attr.id}
                            onChange={(e) =>
                                handleAttributeChange(
                                    index,
                                    "id",
                                    e.target.value
                                )
                            }
                            name={`attributes[${index}][id]`}
                            className={`border ${
                                errors[`attributes.${index}.id`]
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

                        {errors[`attributes.${index}.id`] && (
                            <p className="text-red-500 text-sm">
                                {errors[`attributes.${index}.id`][0]}
                            </p>
                        )}
                    </div>

                    <div>
                        <input
                            type="text"
                            value={attr.value}
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

                    <SecondaryButton
                        onClick={() => removeAttribute(index)}
                        type="button"
                    >
                        Remove
                    </SecondaryButton>
                </div>
            ))}

            {selectedAttributes.length < attributes.length && (
                <PrimaryButton onClick={addAttribute} type="button">
                    Add Attribute
                </PrimaryButton>
            )}
        </div>
    );
};

export default Attributes;
